<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

final class ConvertParserValueVisitor implements \Graphpinator\Parser\Value\ValueVisitor
{
    use \Nette\SmartObject;

    public function __construct(
        private \Graphpinator\Type\Contract\Inputable $type,
        private \Graphpinator\Common\Path $path,
        private ?\Graphpinator\Normalizer\Variable\VariableSet $variableSet,
    ) {}

    public function visitLiteral(\Graphpinator\Parser\Value\Literal $literal) : \Graphpinator\Value\InputedValue
    {
        return $this->type->accept(new ConvertRawValueVisitor($literal->getRawValue()));
    }

    public function visitEnumLiteral(\Graphpinator\Parser\Value\EnumLiteral $enumLiteral) : \Graphpinator\Value\InputedValue
    {
        if ($this->type instanceof \Graphpinator\Type\NotNullType) {
            $this->type = $this->type->getInnerType();

            return $enumLiteral->accept($this);
        }

        if ($this->type instanceof \Graphpinator\Type\EnumType) {
            return $this->type->accept(new ConvertRawValueVisitor($enumLiteral->getRawValue()));
        }

        throw new \Graphpinator\Exception\Value\InvalidValue($this->type->printName(), $enumLiteral->getRawValue(), true);
    }

    public function visitListVal(\Graphpinator\Parser\Value\ListVal $listVal) : \Graphpinator\Value\ListInputedValue
    {
        if ($this->type instanceof \Graphpinator\Type\NotNullType) {
            $this->type = $this->type->getInnerType();

            return $listVal->accept($this);
        }

        if (!$this->type instanceof \Graphpinator\Type\ListType) {
            throw new \Graphpinator\Exception\Value\InvalidValue($this->type->printName(), [], true);
        }

        $inner = [];
        $listType = $this->type;
        $this->type = $this->type->getInnerType();

        foreach ($listVal->getValue() as $index => $parserValue) {
            $this->path->add($index . ' <list index>');
            \assert($parserValue instanceof \Graphpinator\Parser\Value\Value);
            $inner[] = $parserValue->accept($this);
            $this->path->pop();
        }

        $this->type = $listType;

        return new \Graphpinator\Value\ListInputedValue($listType, $inner);
    }

    public function visitObjectVal(\Graphpinator\Parser\Value\ObjectVal $objectVal) : \Graphpinator\Value\InputValue
    {
        if ($this->type instanceof \Graphpinator\Type\NotNullType) {
            $this->type = $this->type->getInnerType();

            return $objectVal->accept($this);
        }

        if (!$this->type instanceof \Graphpinator\Type\InputType) {
            throw new \Graphpinator\Exception\Value\InvalidValue($this->type->printName(), new \stdClass(), true);
        }

        foreach ($objectVal->getValue() as $name => $temp) {
            if ($this->type->getArguments()->offsetExists($name)) {
                continue;
            }

            throw new \Graphpinator\Normalizer\Exception\UnknownArgument($name);
        }

        $inner = new \stdClass();

        foreach ($this->type->getArguments() as $argument) {
            $this->path->add($argument->getName() . ' <input field>');
            $inner->{$argument->getName()} = \property_exists($objectVal->getValue(), $argument->getName())
                ? self::convertArgumentValue($objectVal->getValue()->{$argument->getName()}, $argument, $this->path, $this->variableSet)
                : \Graphpinator\Value\ConvertRawValueVisitor::convertArgument($argument, null);
            $this->path->pop();
        }

        return new \Graphpinator\Value\InputValue($this->type, $inner);
    }

    public function visitVariableRef(\Graphpinator\Parser\Value\VariableRef $variableRef) : \Graphpinator\Value\VariableValue
    {
        if ($this->variableSet instanceof \Graphpinator\Normalizer\Variable\VariableSet) {
            return $this->variableSet->offsetExists($variableRef->getVarName())
                ? new \Graphpinator\Value\VariableValue($this->type, $this->variableSet->offsetGet($variableRef->getVarName()))
                : throw new \Graphpinator\Normalizer\Exception\UnknownVariable($variableRef->getVarName());
        }

        throw new \Graphpinator\Normalizer\Exception\VariableInConstContext();
    }

    public static function convertArgumentValue(
        \Graphpinator\Parser\Value\Value $value,
        \Graphpinator\Argument\Argument $argument,
        \Graphpinator\Common\Path $path,
        ?\Graphpinator\Normalizer\Variable\VariableSet $variableSet,
    ) : \Graphpinator\Value\ArgumentValue
    {
        $default = $argument->getDefaultValue();
        $result = $value->accept(
            new ConvertParserValueVisitor($argument->getType(), $path, $variableSet),
        );

        if ($result instanceof \Graphpinator\Value\NullInputedValue && $default instanceof \Graphpinator\Value\ArgumentValue) {
            return $default;
        }

        return new \Graphpinator\Value\ArgumentValue($argument, $result, true);
    }
}
