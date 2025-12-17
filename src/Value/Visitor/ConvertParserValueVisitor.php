<?php

declare(strict_types = 1);

namespace Graphpinator\Value\Visitor;

use Graphpinator\Common\Path;
use Graphpinator\Normalizer\Exception\UnknownArgument;
use Graphpinator\Normalizer\Exception\UnknownVariable;
use Graphpinator\Normalizer\Exception\VariableInConstContext;
use Graphpinator\Normalizer\Variable\VariableSet;
use Graphpinator\Parser\Value\EnumLiteral;
use Graphpinator\Parser\Value\ListVal;
use Graphpinator\Parser\Value\Literal;
use Graphpinator\Parser\Value\ObjectVal;
use Graphpinator\Parser\Value\ValueVisitor;
use Graphpinator\Parser\Value\VariableRef;
use Graphpinator\Typesystem\Contract\Type;
use Graphpinator\Typesystem\EnumType;
use Graphpinator\Typesystem\InputType;
use Graphpinator\Typesystem\ListType;
use Graphpinator\Typesystem\NotNullType;
use Graphpinator\Typesystem\Visitor\IsInputableVisitor;
use Graphpinator\Typesystem\Visitor\PrintNameVisitor;
use Graphpinator\Value\ArgumentValue;
use Graphpinator\Value\Exception\InvalidValue;
use Graphpinator\Value\Exception\ValueCannotBeNull;
use Graphpinator\Value\InputValue;
use Graphpinator\Value\InputedValue;
use Graphpinator\Value\ListInputedValue;
use Graphpinator\Value\VariableValue;

final readonly class ConvertParserValueVisitor implements ValueVisitor
{
    public function __construct(
        private Type $type,
        private ?VariableSet $variableSet,
        private Path $path,
    )
    {
        \assert($type->accept(new IsInputableVisitor()));
    }

    #[\Override]
    public function visitLiteral(Literal $literal) : InputedValue
    {
        return $this->type->accept(new ConvertRawValueVisitor($literal->getRawValue(), $this->path));
    }

    #[\Override]
    public function visitEnumLiteral(EnumLiteral $enumLiteral) : InputedValue
    {
        if ($this->type instanceof NotNullType) {
            return $enumLiteral->accept(new self($this->type->getInnerType(), $this->variableSet, $this->path));
        }

        if (!$this->type instanceof EnumType) {
            throw new InvalidValue($this->type->accept(new PrintNameVisitor()), $enumLiteral->getRawValue(), true);
        }

        return $this->type->accept(new ConvertRawValueVisitor($enumLiteral->getRawValue(), $this->path));
    }

    #[\Override]
    public function visitListVal(ListVal $listVal) : ListInputedValue
    {
        if ($this->type instanceof NotNullType) {
            return $listVal->accept(new self($this->type->getInnerType(), $this->variableSet, $this->path));
        }

        if (!$this->type instanceof ListType) {
            throw new InvalidValue($this->type->accept(new PrintNameVisitor()), [], true);
        }

        $visitor = new self($this->type->getInnerType(), $this->variableSet, $this->path);
        $inner = [];

        foreach ($listVal->value as $index => $parserValue) {
            $this->path->add($index . ' <list index>');
            $inner[] = $parserValue->accept($visitor);
            $this->path->pop();
        }

        return new ListInputedValue($this->type, $inner);
    }

    #[\Override]
    public function visitObjectVal(ObjectVal $objectVal) : InputValue
    {
        if ($this->type instanceof NotNullType) {
            return $objectVal->accept(new self($this->type->getInnerType(), $this->variableSet, $this->path));
        }

        if (!$this->type instanceof InputType) {
            throw new InvalidValue($this->type->accept(new PrintNameVisitor()), new \stdClass(), true);
        }

        foreach ((array) $objectVal->value as $name => $temp) {
            if ($this->type->getArguments()->offsetExists($name)) {
                continue;
            }

            throw new UnknownArgument($name);
        }

        $inner = new \stdClass();

        // ->toArray() call must be present, otherwise the internal iterator pointer would reset on nested inputs of the same type
        foreach ($this->type->getArguments()->toArray() as $argument) {
            $this->path->add($argument->getName() . ' <input field>');

            if (\property_exists($objectVal->value, $argument->getName())) {
                $result = $objectVal->value->{$argument->getName()}->accept(
                    new ConvertParserValueVisitor($argument->getType(), $this->variableSet, $this->path),
                );

                $inner->{$argument->getName()} = new ArgumentValue($argument, $result, true);
                $this->path->pop();

                continue;
            }

            $default = $argument->getDefaultValue();

            if ($default instanceof ArgumentValue) {
                $inner->{$argument->getName()} = $default;
            } elseif ($argument->getType() instanceof NotNullType) {
                throw new ValueCannotBeNull(true);
            }

            $this->path->pop();
        }

        return new InputValue($this->type, $inner);
    }

    #[\Override]
    public function visitVariableRef(VariableRef $variableRef) : VariableValue
    {
        if ($this->variableSet instanceof VariableSet) {
            return $this->variableSet->offsetExists($variableRef->varName)
                ? new VariableValue($this->type, $this->variableSet->offsetGet($variableRef->varName))
                : throw new UnknownVariable($variableRef->varName);
        }

        throw new VariableInConstContext();
    }
}
