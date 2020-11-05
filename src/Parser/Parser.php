<?php

declare(strict_types = 1);

namespace Graphpinator\Parser;

use \Graphpinator\Tokenizer\TokenType;

final class Parser
{
    use \Nette\SmartObject;

    private \Graphpinator\Tokenizer\TokenContainer $tokenizer;

    public function __construct(\Graphpinator\Source\Source $source)
    {
        $this->tokenizer = new \Graphpinator\Tokenizer\TokenContainer($source);
    }

    /**
     * Static shortcut.
     * @param string $source
     */
    public static function parseString(string $source) : ParseResult
    {
        return (new self(new \Graphpinator\Source\StringSource($source)))->parse();
    }

    /**
     * Parses document and produces ParseResult object.
     */
    public function parse() : ParseResult
    {
        if ($this->tokenizer->isEmpty()) {
            throw new \Graphpinator\Exception\Parser\EmptyRequest(new \Graphpinator\Source\Location(1, 1));
        }

        $fragments = [];
        $locations = [];
        $operations = [];

        while (true) {
            if ($this->tokenizer->getCurrent()->getType() === TokenType::FRAGMENT) {
                $fragment = $this->parseFragmentDefinition();
                $fragments[$fragment->getName()] = $fragment;
            } else {
                $location = $this->tokenizer->getCurrent()->getLocation();
                $operation = $this->parseOperation();

                if (\array_key_exists($operation->getName(), $operations)) {
                    throw new \Graphpinator\Exception\Parser\DuplicateOperation($location);
                }

                $locations[$operation->getName()] = $location;
                $operations[$operation->getName()] = $operation;
            }

            if (!$this->tokenizer->hasNext()) {
                break;
            }

            $this->tokenizer->getNext();
        }

        switch (\count($operations)) {
            case 0:
                throw new \Graphpinator\Exception\Parser\MissingOperation($this->tokenizer->getCurrent()->getLocation());
            case 1:
                break;
            default:
                foreach ($operations as $operation) {
                    if ($operation->getName() === null) {
                        throw new \Graphpinator\Exception\Parser\OperationWithoutName($locations[$operation->getName()]);
                    }
                }
        }

        return new \Graphpinator\Parser\ParseResult(
            new \Graphpinator\Parser\Operation\OperationSet($operations),
            new \Graphpinator\Parser\Fragment\FragmentSet($fragments),
        );
    }

    /**
     * Parses fragment definition after fragment keyword.
     *
     * Expects iterator on previous token - fragment keyword
     * Leaves iterator to last used token - closing brace
     */
    private function parseFragmentDefinition() : \Graphpinator\Parser\Fragment\Fragment
    {
        $fragmentName = $this->tokenizer->assertNext(TokenType::NAME, \Graphpinator\Exception\Parser\ExpectedFragmentName::class)->getValue();
        $this->tokenizer->assertNext(TokenType::ON, \Graphpinator\Exception\Parser\ExpectedTypeCondition::class);
        $typeCond = $this->parseType(true);
        $this->tokenizer->assertNext(TokenType::CUR_O, \Graphpinator\Exception\Parser\ExpectedSelectionSet::class);

        return new \Graphpinator\Parser\Fragment\Fragment(
            $fragmentName,
            $typeCond,
            $this->parseSelectionSet(),
        );
    }

    /**
     * Parses operation
     *
     * Expects iterator on previous token - operation keyword or opening brace
     * Leaves iterator to last used token - closing brace
     */
    private function parseOperation() : \Graphpinator\Parser\Operation\Operation
    {
        switch ($this->tokenizer->getCurrent()->getType()) {
            case TokenType::CUR_O:
                return new \Graphpinator\Parser\Operation\Operation($this->parseSelectionSet());
            case TokenType::NAME:
                $operationType = $this->tokenizer->getCurrent()->getValue();

                if (!\Graphpinator\Tokenizer\OperationType::isOperationKeyword($operationType)) {
                    throw new \Graphpinator\Exception\Parser\UnknownOperationType($this->tokenizer->getCurrent()->getLocation());
                }

                switch ($this->tokenizer->getNext()->getType()) {
                    case TokenType::CUR_O:
                        return new \Graphpinator\Parser\Operation\Operation($this->parseSelectionSet(), $operationType);
                    case TokenType::NAME:
                        $operationName = $this->tokenizer->getCurrent()->getValue();

                        switch ($this->tokenizer->getNext()->getType()) {
                            case TokenType::CUR_O:
                                return new \Graphpinator\Parser\Operation\Operation($this->parseSelectionSet(), $operationType, $operationName);
                            case TokenType::PAR_O:
                                $variables = $this->parseVariables();
                                $this->tokenizer->assertNext(
                                    TokenType::CUR_O,
                                    \Graphpinator\Exception\Parser\ExpectedSelectionSet::class,
                                );

                                return new \Graphpinator\Parser\Operation\Operation(
                                    $this->parseSelectionSet(),
                                    $operationType,
                                    $operationName,
                                    $variables,
                                );
                            default:
                                throw new \Graphpinator\Exception\Parser\ExpectedAfterOperationName($this->tokenizer->getCurrent()->getLocation());
                        }

                        break;
                    default:
                        throw new \Graphpinator\Exception\Parser\ExpectedAfterOperationType($this->tokenizer->getCurrent()->getLocation());
                }

                break;
            default:
                throw new \Graphpinator\Exception\Parser\ExpectedRoot($this->tokenizer->getCurrent()->getLocation());
        }
    }

    /**
     * Parses selection set.
     *
     * Expects iterator on previous token - opening brace
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
                    $this->tokenizer->getNext();
                    $fields[] = $this->parseField();

                    break;
                default:
                    throw new \Graphpinator\Exception\Parser\ExpectedSelectionSetBody($this->tokenizer->getNext()->getLocation());
            }
        }

        $this->tokenizer->getNext();

        return new \Graphpinator\Parser\FieldSet($fields, new \Graphpinator\Parser\FragmentSpread\FragmentSpreadSet($fragments));
    }

    /**
     * Parses single field.
     *
     * Expects iterator on previous token - field name
     * Leaves iterator to last used token - last token in field definition
     */
    private function parseField() : Field
    {
        $fieldName = $this->tokenizer->getCurrent()->getValue();
        $aliasName = null;
        $arguments = null;
        $children = null;

        if ($this->tokenizer->peekNext()->getType() === TokenType::COLON) {
            $this->tokenizer->getNext();

            $aliasName = $fieldName;
            $fieldName = $this->tokenizer->assertNext(TokenType::NAME, \Graphpinator\Exception\Parser\ExpectedFieldName::class)->getValue();
        }

        if ($this->tokenizer->peekNext()->getType() === TokenType::PAR_O) {
            $this->tokenizer->getNext();
            $arguments = $this->parseArguments();
        }

        $directives = $this->parseDirectives(\Graphpinator\Directive\ExecutableDirectiveLocation::FIELD);

        if ($this->tokenizer->peekNext()->getType() === TokenType::CUR_O) {
            $this->tokenizer->getNext();
            $children = $this->parseSelectionSet();
        }

        return new \Graphpinator\Parser\Field($fieldName, $aliasName, $children, $arguments, $directives);
    }

    /**
     * Parses fragment spread after ellipsis.
     *
     * Expects iterator on previous token - ellipsis
     * Leaves iterator to last used token - either fragment name or closing brace
     */
    private function parseFragmentSpread() : \Graphpinator\Parser\FragmentSpread\FragmentSpread
    {
        switch ($this->tokenizer->getNext()->getType()) {
            case TokenType::NAME:
                return new \Graphpinator\Parser\FragmentSpread\NamedFragmentSpread(
                    $this->tokenizer->getCurrent()->getValue(),
                    $this->parseDirectives(\Graphpinator\Directive\ExecutableDirectiveLocation::FRAGMENT_SPREAD),
                );
            case TokenType::ON:
                $typeCond = $this->parseType(true);
                $directives = $this->parseDirectives(\Graphpinator\Directive\ExecutableDirectiveLocation::INLINE_FRAGMENT);
                $this->tokenizer->assertNext(TokenType::CUR_O, \Graphpinator\Exception\Parser\ExpectedSelectionSet::class);

                return new \Graphpinator\Parser\FragmentSpread\InlineFragmentSpread(
                    $this->parseSelectionSet(),
                    $directives,
                    $typeCond,
                );
            case TokenType::DIRECTIVE:
                $this->tokenizer->getPrev();
                $directives = $this->parseDirectives(\Graphpinator\Directive\ExecutableDirectiveLocation::INLINE_FRAGMENT);
                $this->tokenizer->assertNext(TokenType::CUR_O, \Graphpinator\Exception\Parser\ExpectedSelectionSet::class);

                return new \Graphpinator\Parser\FragmentSpread\InlineFragmentSpread(
                    $this->parseSelectionSet(),
                    $directives,
                    null,
                );
            default:
                throw new \Graphpinator\Exception\Parser\ExpectedFragmentSpreadInfo($this->tokenizer->getCurrent()->getLocation());
        }
    }

    /**
     * Parses variables definition.
     *
     * Expects iterator on previous token - opening parenthesis
     * Leaves iterator to last used token - closing parenthesis
     */
    private function parseVariables() : \Graphpinator\Parser\Variable\VariableSet
    {
        $variables = [];

        while ($this->tokenizer->peekNext()->getType() !== TokenType::PAR_C) {
            if ($this->tokenizer->getNext()->getType() !== TokenType::VARIABLE) {
                throw new \Graphpinator\Exception\Parser\ExpectedVariableName($this->tokenizer->getCurrent()->getLocation());
            }

            $name = $this->tokenizer->getCurrent()->getValue();
            $this->tokenizer->assertNext(TokenType::COLON, \Graphpinator\Exception\Parser\ExpectedColon::class);
            $type = $this->parseType(false);
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
     * Parses directive list.
     *
     * Expects iterator on previous token
     * Leaves iterator to last used token - closing parenthesis
     *
     * @param string $location
     */
    private function parseDirectives(string $location) : \Graphpinator\Parser\Directive\DirectiveSet
    {
        $directives = [];

        while ($this->tokenizer->peekNext()->getType() === TokenType::DIRECTIVE) {
            $this->tokenizer->getNext();

            $dirName = $this->tokenizer->getCurrent()->getValue();
            $dirArguments = null;

            if ($this->tokenizer->peekNext()->getType() === TokenType::PAR_O) {
                $this->tokenizer->getNext();
                $dirArguments = $this->parseArguments();
            }

            $directives[] = new \Graphpinator\Parser\Directive\Directive($dirName, $dirArguments);
        }

        return new \Graphpinator\Parser\Directive\DirectiveSet($directives, $location);
    }

    /**
     * Parses argument list.
     *
     * Expects iterator on previous token - opening parenthesis
     * Leaves iterator to last used token - closing parenthesis
     */
    private function parseArguments() : \Graphpinator\Parser\Value\NamedValueSet
    {
        $arguments = [];

        while ($this->tokenizer->peekNext()->getType() !== TokenType::PAR_C) {
            if ($this->tokenizer->getNext()->getType() !== TokenType::NAME) {
                throw new \Graphpinator\Exception\Parser\ExpectedArgumentName($this->tokenizer->getCurrent()->getLocation());
            }

            $name = $this->tokenizer->getCurrent()->getValue();
            $this->tokenizer->assertNext(TokenType::COLON, \Graphpinator\Exception\Parser\ExpectedColon::class);
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
     *
     * @param bool $literalOnly
     */
    private function parseValue(bool $literalOnly) : \Graphpinator\Parser\Value\Value
    {
        switch ($this->tokenizer->getNext()->getType()) {
            case TokenType::VARIABLE:
                if ($literalOnly) {
                    throw new \Graphpinator\Exception\Parser\ExpectedLiteralValue($this->tokenizer->getCurrent()->getLocation());
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
                $values = new \stdClass();

                while ($this->tokenizer->peekNext()->getType() !== TokenType::CUR_C) {
                    $name = $this->tokenizer->assertNext(TokenType::NAME, \Graphpinator\Exception\Parser\ExpectedFieldName::class)->getValue();
                    $this->tokenizer->assertNext(TokenType::COLON, \Graphpinator\Exception\Parser\ExpectedColon::class);
                    $values->{$name} = $this->parseValue($literalOnly);
                }

                $this->tokenizer->getNext();

                return new \Graphpinator\Parser\Value\ObjectVal($values);
            default:
                throw new \Graphpinator\Exception\Parser\ExpectedValue($this->tokenizer->getNext()->getLocation());
        }
    }

    /**
     * Parses type reference.
     *
     * Expects iterator on previous token
     * Leaves iterator to last used token - last token in type definition
     *
     * @param bool $namedOnly
     */
    private function parseType(bool $namedOnly) : \Graphpinator\Parser\TypeRef\TypeRef
    {
        $type = null;

        switch ($this->tokenizer->getNext()->getType()) {
            case TokenType::NAME:
                $type = new \Graphpinator\Parser\TypeRef\NamedTypeRef($this->tokenizer->getCurrent()->getValue());

                break;
            case TokenType::SQU_O:
                $type = new \Graphpinator\Parser\TypeRef\ListTypeRef($this->parseType(false));
                $this->tokenizer->assertNext(TokenType::SQU_C, \Graphpinator\Exception\Parser\ExpectedClosingBracket::class);

                break;
            default:
                throw new \Graphpinator\Exception\Parser\ExpectedType($this->tokenizer->getNext()->getLocation());
        }

        if ($this->tokenizer->peekNext()->getType() === TokenType::EXCL) {
            $this->tokenizer->getNext();

            $type = new \Graphpinator\Parser\TypeRef\NotNullRef($type);
        }

        if ($namedOnly && !$type instanceof \Graphpinator\Parser\TypeRef\NamedTypeRef) {
            throw new \Graphpinator\Exception\Parser\ExpectedNamedType($this->tokenizer->getNext()->getLocation());
        }

        return $type;
    }
}
