<?php

declare(strict_types = 1);

namespace Graphpinator\Parser;

use Graphpinator\Tokenizer\TokenType;

final class Parser
{
    use \Nette\SmartObject;

    private \Graphpinator\Tokenizer\TokenContainer $tokenizer;

    public function __construct(string $source)
    {
        $this->tokenizer = new \Graphpinator\Tokenizer\TokenContainer($source);
    }

    public static function parseRequest(string $source) : ParseResult
    {
        return (new self($source))->parse();
    }

    /**
     * Parses document and produces ParseResult object.
     */
    public function parse() : ParseResult
    {
        if ($this->tokenizer->isEmpty()) {
            throw new \Exception('Empty input.');
        }

        $fragments = [];
        $operation = null;

        while (true) {
            switch ($this->tokenizer->getCurrent()->getType()) {
                case TokenType::FRAGMENT:
                    $fragment = $this->parseFragmentDefinition();
                    $fragments[$fragment->getName()] = $fragment;

                    break;
                case TokenType::CUR_O:
                    $operation = new Operation($this->parseSelectionSet());

                    break;
                case TokenType::NAME:
                    $operationType = $this->tokenizer->getCurrent()->getValue();

                    if (!\Graphpinator\Tokenizer\OperationType::isOperationKeyword($operationType)) {
                        throw new \Exception('Unknown operation type');
                    }

                    switch ($this->tokenizer->getNext()->getType()) {
                        case TokenType::CUR_O:
                            $operation = new Operation($this->parseSelectionSet(), $operationType);

                            break;
                        case TokenType::NAME:
                            $operationName = $this->tokenizer->getCurrent()->getValue();

                            switch ($this->tokenizer->getNext()->getType()) {
                                case TokenType::CUR_O:
                                    $operation = new Operation($this->parseSelectionSet(), $operationType, $operationName);

                                    break;
                                case TokenType::PAR_O:
                                    $variables = $this->parseVariables();
                                    $this->tokenizer->assertNext(TokenType::CUR_O);

                                    $operation = new Operation($this->parseSelectionSet(), $operationType, $operationName, $variables);

                                    break;
                                default:
                                    throw new \Exception('Expected selection set or variables');
                            }

                            break;
                        default:
                            throw new \Exception('Expected selection set or operation name.');
                    }

                    break;
                default:
                    throw new \Exception('Expected selection set, operation type or fragment definition.');
            }

            if (!$this->tokenizer->hasNext()) {
                break;
            }

            $this->tokenizer->getNext();
        }

        if (!$operation instanceof Operation) {
            throw new \Exception('No operation provided in document');
        }

        return new ParseResult(
            $operation,
            new \Graphpinator\Parser\Fragment\FragmentSet($fragments),
        );
    }

    /**
     * Parses fragment definition after fragment keyword.
     *
     * Expects iterator on previous token
     * Leaves iterator to last used token - closing brace
     */
    private function parseFragmentDefinition() : \Graphpinator\Parser\Fragment\Fragment
    {
        $fragmentName = $this->tokenizer->assertNext(TokenType::NAME)->getValue();
        $this->tokenizer->assertNext(TokenType::ON);
        $typeCond = $this->tokenizer->assertNext(TokenType::NAME)->getValue();
        $this->tokenizer->assertNext(TokenType::CUR_O);

        return new \Graphpinator\Parser\Fragment\Fragment(
            $fragmentName,
            new \Graphpinator\Parser\TypeRef\NamedTypeRef($typeCond),
            $this->parseSelectionSet(),
        );
    }

    /**
     * Parses selection set.
     *
     * Expects iterator on previous token
     * Leaves iterator to last used token - closing brace
     */
    private function parseSelectionSet() : FieldSet
    {
        $fields = [];
        $fragments = [];

        while ($this->tokenizer->peekNext()->getType() !== TokenType::CUR_C) {
            switch ($this->tokenizer->peekNext()->getType()) {
                case TokenType::ELLIP:
                    $this->tokenizer->getNext();
                    $fragments[] = $this->parseFragmentSpread();

                    break;
                case TokenType::NAME:
                    $fields[] = $this->parseField();

                    break;
                default:
                    throw new \Exception('Expected field, fragment expansion or }');
            }
        }

        $this->tokenizer->getNext();

        return new FieldSet($fields, new \Graphpinator\Parser\FragmentSpread\FragmentSpreadSet($fragments));
    }

    /**
     * Parses single field.
     *
     * Expects iterator on previous token
     * Leaves iterator to last used token - last token in field definition
     */
    private function parseField() : Field
    {
        $fieldName = $this->tokenizer->getNext()->getValue();
        $aliasName = null;
        $arguments = null;
        $children = null;

        if ($this->tokenizer->peekNext()->getType() === TokenType::COLON) {
            $this->tokenizer->getNext();
            $token = $this->tokenizer->assertNext(TokenType::NAME);

            $aliasName = $fieldName;
            $fieldName = $token->getValue();
        }

        if ($this->tokenizer->peekNext()->getType() === TokenType::PAR_O) {
            $this->tokenizer->getNext();
            $arguments = $this->parseArguments();
        }

        if ($this->tokenizer->peekNext()->getType() === TokenType::DIRECTIVE) {
            throw new \Exception('Directives are not yet supported');
        }

        if ($this->tokenizer->peekNext()->getType() === TokenType::CUR_O) {
            $this->tokenizer->getNext();
            $children = $this->parseSelectionSet();
        }

        return new Field($fieldName, $aliasName, $children, $arguments);
    }

    /**
     * Parses fragment spread after ellipsis.
     *
     * Expects iterator on previous token
     * Leaves iterator to last used token - either fragment name or closing brace
     */
    private function parseFragmentSpread() : \Graphpinator\Parser\FragmentSpread\FragmentSpread
    {
        switch ($this->tokenizer->getNext()->getType()) {
            case TokenType::NAME:
                return new \Graphpinator\Parser\FragmentSpread\NamedFragmentSpread($this->tokenizer->getCurrent()->getValue());
            case TokenType::ON:
                $typeCond = $this->parseType();
                $this->tokenizer->assertNext(TokenType::CUR_O);

                return new \Graphpinator\Parser\FragmentSpread\TypeFragmentSpread($typeCond, $this->parseSelectionSet());
            default:
                throw new \Exception('Expected fragment name or type condition.');
        }
    }

    /**
     * Parses variables definition.
     *
     * Expects iterator on previous token
     * Leaves iterator to last used token - closing parenthesis
     */
    private function parseVariables() : \Graphpinator\Parser\Variable\VariableSet
    {
        $variables = [];

        while ($this->tokenizer->peekNext()->getType() !== TokenType::PAR_C) {
            if ($this->tokenizer->getNext()->getType() !== TokenType::VARIABLE) {
                throw new \Exception('Expected variable definition');
            }

            $name = $this->tokenizer->getCurrent()->getValue();
            $this->tokenizer->assertNext(TokenType::COLON);
            $type = $this->parseType();
            $default = null;

            if ($this->tokenizer->peekNext()->getType() === TokenType::EQUAL) {
                $this->tokenizer->getNext();
                $default = $this->parseValue(true);
            }

            $variables[] = new \Graphpinator\Parser\Variable\Variable($name, $type, $default);
        }

        $this->tokenizer->getNext();

        return new \Graphpinator\Parser\Variable\VariableSet($variables);
    }

    /**
     * Parses argument list.
     *
     * Expects iterator on previous token
     * Leaves iterator to last used token - closing parenthesis
     */
    private function parseArguments() : \Graphpinator\Parser\Value\NamedValueSet
    {
        $arguments = [];

        while ($this->tokenizer->peekNext()->getType() !== TokenType::PAR_C) {
            if ($this->tokenizer->getNext()->getType() !== TokenType::NAME) {
                throw new \Exception('Expected argument name');
            }

            $name = $this->tokenizer->getCurrent()->getValue();
            $this->tokenizer->assertNext(TokenType::COLON);
            $value = $this->parseValue(false);

            $arguments[] = new \Graphpinator\Parser\Value\NamedValue($value, $name);
        }

        $this->tokenizer->getNext();

        return new \Graphpinator\Parser\Value\NamedValueSet($arguments);
    }

    /**
     * Parses value - either literal value or variable.
     *
     * Expects iterator on previous token
     * Leaves iterator to last used token - last token in value definition
     */
    private function parseValue(bool $literalOnly = false) : \Graphpinator\Parser\Value\Value
    {
        switch ($this->tokenizer->getNext()->getType()) {
            case TokenType::VARIABLE:
                if ($literalOnly) {
                    throw new \Exception('Only literal values are allowed here.');
                }

                return new \Graphpinator\Parser\Value\VariableRef($this->tokenizer->getCurrent()->getValue());
            case TokenType::STRING:
                return new \Graphpinator\Parser\Value\Literal($this->tokenizer->getCurrent()->getValue());
            case TokenType::INT:
                return new \Graphpinator\Parser\Value\Literal((int) $this->tokenizer->getCurrent()->getValue());
            case TokenType::FLOAT:
                return new \Graphpinator\Parser\Value\Literal((float) $this->tokenizer->getCurrent()->getValue());
            case TokenType::TRUE:
                return new \Graphpinator\Parser\Value\Literal(true);
            case TokenType::FALSE:
                return new \Graphpinator\Parser\Value\Literal(false);
            case TokenType::NULL:
                return new \Graphpinator\Parser\Value\Literal(null);
            case TokenType::SQU_O:
                $values = [];

                while ($this->tokenizer->peekNext()->getType() !== TokenType::SQU_C) {
                    $values[] = $this->parseValue($literalOnly);
                }

                $this->tokenizer->getNext();

                return new \Graphpinator\Parser\Value\ListVal($values);
            case TokenType::CUR_O:
                $values = [];

                while ($this->tokenizer->peekNext()->getType() !== TokenType::CUR_C) {
                    $name = $this->tokenizer->assertNext(TokenType::NAME)->getValue();
                    $this->tokenizer->assertNext(TokenType::COLON);
                    $values[$name] = $this->parseValue($literalOnly);
                }

                $this->tokenizer->getNext();

                return new \Graphpinator\Parser\Value\ObjectVal($values);
            default:
                throw new \Exception('Expected value');
        }
    }

    /**
     * Parses type reference.
     *
     * Expects iterator on previous token
     * Leaves iterator to last used token - last token in type definition
     */
    private function parseType() : \Graphpinator\Parser\TypeRef\TypeRef
    {
        $type = null;

        switch ($this->tokenizer->getNext()->getType()) {
            case TokenType::NAME:
                $type = new \Graphpinator\Parser\TypeRef\NamedTypeRef($this->tokenizer->getCurrent()->getValue());

                break;
            case TokenType::SQU_O:
                $type = new \Graphpinator\Parser\TypeRef\ListTypeRef($this->parseType());
                $this->tokenizer->assertNext(TokenType::SQU_C);

                break;
            default:
                throw new \Exception('Expected type reference');
        }

        if ($this->tokenizer->peekNext()->getType() === TokenType::EXCL) {
            $this->tokenizer->getNext();

            return new \Graphpinator\Parser\TypeRef\NotNullRef($type);
        }

        return $type;
    }
}
