<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

use Graphpinator\Common\Path;
use Graphpinator\Exception\Value\InvalidValue;
use Graphpinator\Exception\Value\ValueCannotBeNull;
use Graphpinator\Normalizer\Exception\UnknownArgument;
use Graphpinator\Normalizer\Exception\UnknownVariable;
use Graphpinator\Normalizer\Exception\VariableInConstContext;
use Graphpinator\Normalizer\Variable\VariableSet;
use Graphpinator\Parser\Value\EnumLiteral;
use Graphpinator\Parser\Value\ListVal;
use Graphpinator\Parser\Value\Literal;
use Graphpinator\Parser\Value\ObjectVal;
use Graphpinator\Parser\Value\Value;
use Graphpinator\Parser\Value\ValueVisitor;
use Graphpinator\Parser\Value\VariableRef;
use Graphpinator\Typesystem\Contract\Inputable;
use Graphpinator\Typesystem\InputType;
use Graphpinator\Typesystem\ListType;
use Graphpinator\Typesystem\NotNullType;

final class ConvertParserValueVisitor implements ValueVisitor
{
    public function __construct(
        private Inputable $type,
        private ?VariableSet $variableSet,
        private Path $path,
    )
    {
    }

    public function visitLiteral(Literal $literal) : InputedValue
    {
        return $this->type->accept(new ConvertRawValueVisitor($literal->getRawValue(), $this->path));
    }

    public function visitEnumLiteral(EnumLiteral $enumLiteral) : InputedValue
    {
        return $this->type->accept(new ConvertRawValueVisitor($enumLiteral->getRawValue(), $this->path));
    }

    public function visitListVal(ListVal $listVal) : ListInputedValue
    {
        if ($this->type instanceof NotNullType) {
            $this->type = $this->type->getInnerType();

            return $listVal->accept($this);
        }

        if (!$this->type instanceof ListType) {
            throw new InvalidValue($this->type->printName(), [], true);
        }

        $inner = [];
        $listType = $this->type;
        $this->type = $this->type->getInnerType();

        foreach ($listVal->getValue() as $index => $parserValue) {
            $this->path->add($index . ' <list index>');
            \assert($parserValue instanceof Value);
            $inner[] = $parserValue->accept($this);
            $this->path->pop();
        }

        $this->type = $listType;

        return new ListInputedValue($listType, $inner);
    }

    public function visitObjectVal(ObjectVal $objectVal) : InputValue
    {
        if ($this->type instanceof NotNullType) {
            $this->type = $this->type->getInnerType();

            return $objectVal->accept($this);
        }

        if (!$this->type instanceof InputType) {
            throw new InvalidValue($this->type->printName(), new \stdClass(), true);
        }

        foreach ((array) $objectVal->getValue() as $name => $temp) {
            if ($this->type->getArguments()->offsetExists($name)) {
                continue;
            }

            throw new UnknownArgument($name);
        }

        $inner = new \stdClass();

        foreach ($this->type->getArguments() as $argument) {
            $this->path->add($argument->getName() . ' <input field>');

            if (\property_exists($objectVal->getValue(), $argument->getName())) {
                $result = $objectVal->getValue()->{$argument->getName()}->accept(
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

    public function visitVariableRef(VariableRef $variableRef) : VariableValue
    {
        if ($this->variableSet instanceof VariableSet) {
            return $this->variableSet->offsetExists($variableRef->getVarName())
                ? new VariableValue($this->type, $this->variableSet->offsetGet($variableRef->getVarName()))
                : throw new UnknownVariable($variableRef->getVarName());
        }

        throw new VariableInConstContext();
    }
}
