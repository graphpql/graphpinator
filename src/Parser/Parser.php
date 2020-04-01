<?php

declare(strict_types = 1);

namespace Graphpinator\Parser;

use Graphpinator\Tokenizer\TokenType;

final class Parser
{
    use \Nette\SmartObject;

    private \Graphpinator\Tokenizer\TokenContainer $tokenizer;
    private ?Operation $operation = null;
    private array $fragments = [];

    public function __construct(string $source)
    {
        $this->tokenizer = new \Graphpinator\Tokenizer\TokenContainer($source);
    }

    public function parse() : ParseResult
    {
        $this->parseDocument();

        if (!$this->operation instanceof Operation) {
            throw new \Exception('No operation provided in document');
        }

        return new ParseResult(
            $this->operation,
            $this->fragments,
        );
    }

    private function parseDocument() : void
    {
        if ($this->tokenizer->isEmpty()) {
            throw new \Exception('Empty input.');
        }

        while (true) {
            switch ($this->tokenizer->getCurrent()->getType()) {
                case TokenType::FRAGMENT:
                    $this->parseFragmentDefinition();

                    break;
                case TokenType::CUR_O:
                    $this->operation = new Operation($this->parseSelectionSet());

                    break;
                case TokenType::OPERATION:
                    $operationType = $this->tokenizer->getCurrent()->getValue();

                    switch ($this->tokenizer->getNext()->getType()) {
                        case TokenType::CUR_O:
                            $this->operation = new Operation($this->parseSelectionSet(), $operationType);

                            break;
                        case TokenType::NAME:
                            $operationName = $this->tokenizer->getCurrent()->getValue();

                            switch ($this->tokenizer->getNext()->getType()) {
                                case TokenType::CUR_O:
                                    $this->operation = new Operation($this->parseSelectionSet(), $operationType, $operationName);

                                    break;
                                case TokenType::PAR_O:
                                    $variables = $this->parseVariableDefinition();
                                    $this->tokenizer->assertNext(TokenType::CUR_O);

                                    $this->operation = new Operation($this->parseSelectionSet(), $operationType, $operationName, $variables);

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
    }

    private function parseSelectionSet() : FieldSet
    {
        $fields = [];
        $fragments = [];

        while (true) {
            switch ($this->tokenizer->getNext()->getType()) {
                case TokenType::CUR_C:
                    break 2;
                case TokenType::ELLIP:
                    $fragments[] = $this->parseFragmentSpread();

                    break;
                case TokenType::NAME:
                    $fields[] = $this->parseField();

                    break;
                default:
                    throw new \Exception('Expected field, fragment expansion or }');
            }
        }

        return new FieldSet($fields, $fragments);
    }

    private function parseField() : Field
    {
        $fieldName = $this->tokenizer->getCurrent()->getValue();
        $aliasName = null;
        $arguments = null;
        $children = null;

        $token = $this->tokenizer->getNext();

        if ($token->getType() === TokenType::COLON) {
            $token = $this->tokenizer->assertNext(TokenType::NAME);

            $aliasName = $fieldName;
            $fieldName = $token->getValue();

            $token = $this->tokenizer->getNext();
        }

        if ($token->getType() === TokenType::PAR_O) {
            $arguments = $this->parseArguments();

            $token = $this->tokenizer->getNext();
        }

        if ($token->getType() === TokenType::DIRECTIVE) {
            throw new \Exception('Directives are not yet supported');
        }

        if ($token->getType() === TokenType::CUR_O) {
            $children = $this->parseSelectionSet();

            $this->tokenizer->getNext();
        }

        return new Field($fieldName, $aliasName, $children, $arguments);
    }

    /**
     * Parses fragment spread after ellipsis.
     *
     * Expects iterator on previous token
     * Leaves iterator to last used token
     */
    private function parseFragmentSpread() : FragmentSpread
    {
        switch ($this->tokenizer->getNext()->getType()) {
            case TokenType::NAME:
                return new NamedFragmentSpread($this->tokenizer->getCurrent()->getValue());
            case TokenType::ON:
                $typeCond = $this->tokenizer->assertNext(TokenType::NAME)->getValue();
                $this->tokenizer->assertNext(TokenType::CUR_O);

                return new TypeFragmentSpread($typeCond, $this->parseSelectionSet());
            default:
                throw new \Exception('Expected fragment name or type condition.');
        }
    }

    private function parseArguments() : \Graphpinator\Value\GivenValueSet
    {

    }

    /**
     * Parses type reference.
     *
     * Expects iterator on previous token
     * Leaves iterator to last used token
     */
    private function parseType() : \Graphpinator\Parser\TypeRef\TypeRef
    {
        $type = null;

        switch ($this->tokenizer->getNext()->getType()) {
            case TokenType::SQU_O:
                $type = new \Graphpinator\Parser\TypeRef\ListTypeRef($this->parseType());
                $this->tokenizer->assertNext(TokenType::SQU_C);

                break;
            case TokenType::NAME:
                $type = new \Graphpinator\Parser\TypeRef\NamedTypeRef($this->tokenizer->getCurrent()->getValue());

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

    private function parseVariableDefinition() : array
    {
        $variables = [];

        while (true) {
            switch ($this->tokenizer->getNext()->getType()) {
                case TokenType::PAR_C:
                    break 2;
                case TokenType::VARIABLE:
                    $name = $this->tokenizer->getCurrent()->getValue();
                    $this->tokenizer->assertNext(TokenType::COLON);
                    $type = $this->parseType();
                    $default = null;

                    if ($this->tokenizer->peekNext()->getType() === TokenType::EQUAL) {
                        $this->tokenizer->getNext();
                        $default = $this->parseLiteral();
                    }

                    $variables[] = new Variable($name, $type, $default);

                    break;
                default:
                    throw new \Exception('Expected variable definition');
            }
        }

        return $variables;
    }

    private function parseLiteral() : \Graphpinator\Parser\Value\Value
    {
        switch ($this->tokenizer->getNext()->getType()) {
            case TokenType::STRING:
            case TokenType::INT:
            case TokenType::FLOAT:
                return new \Graphpinator\Parser\Value\Literal($this->tokenizer->getCurrent()->getValue());
            case TokenType::TRUE:
                return new \Graphpinator\Parser\Value\Literal(true);
            case TokenType::FALSE:
                return new \Graphpinator\Parser\Value\Literal(false);
            case TokenType::NULL:
                return new \Graphpinator\Parser\Value\Literal(null);
            case TokenType::SQU_O:
                $values = [];

                while ($this->tokenizer->peekNext()->getType() !== TokenType::SQU_C) {
                    $values[] = $this->parseLiteral();
                }

                $this->tokenizer->getNext();

                return new \Graphpinator\Parser\Value\ListVal($values);
            case TokenType::CUR_O:
                $values = [];

                while ($this->tokenizer->peekNext()->getType() !== TokenType::CUR_C) {
                    $name = $this->tokenizer->assertNext(TokenType::NAME)->getValue();
                    $this->tokenizer->assertNext(TokenType::COLON);
                    $values[$name] = $this->parseLiteral();
                }

                $this->tokenizer->getNext();

                return new \Graphpinator\Parser\Value\ObjectVal($values);
            default:
                throw new \Exception('Invalid literal');
        }
    }

    private function parseValue() : \Graphpinator\Parser\Value\Value
    {

    }

    /**
     * Parses fragment definition after fragment keyword.
     *
     * Expects iterator on previous token
     * Leaves iterator to last used token
     */
    private function parseFragmentDefinition() : void
    {
        $fragmentName = $this->tokenizer->assertNext(TokenType::NAME)->getValue();
        $this->tokenizer->assertNext(TokenType::ON);
        $typeCond = $this->tokenizer->assertNext(TokenType::NAME)->getValue();
        $this->tokenizer->assertNext(TokenType::CUR_O);

        $this->fragments[$fragmentName] = new Fragment(
            $fragmentName,
            new \Graphpinator\Parser\TypeRef\NamedTypeRef($typeCond),
            $this->parseSelectionSet()
        );
    }
}
