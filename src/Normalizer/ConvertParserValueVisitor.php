<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer;

final class ConvertParserValueVisitor implements \Graphpinator\Parser\Value\ValueVisitor
{
    use \Nette\SmartObject;

    public function __construct(
        private \Graphpinator\Type\Contract\Inputable $type,
        private \Graphpinator\Normalizer\Variable\VariableSet $variableSet,
        private \Graphpinator\Common\Path $path,
    ) {}

    public static function convertArgumentValue(
        \Graphpinator\Parser\Value\Value $value,
        \Graphpinator\Argument\Argument $argument,
        \Graphpinator\Normalizer\Variable\VariableSet $variableSet,
        \Graphpinator\Common\Path $path,
    ) : \Graphpinator\Value\ArgumentValue
    {
        $default = $argument->getDefaultValue();
        $result = $value->accept(
            new ConvertParserValueVisitor($argument->getType(), $variableSet, $path),
        );

        if ($result instanceof \Graphpinator\Value\NullInputedValue && $default instanceof \Graphpinator\Value\InputedValue) {
            return \Graphpinator\Value\ArgumentValue::fromInputed($argument, $default);
        }

        return \Graphpinator\Value\ArgumentValue::fromInputed($argument, $result);
    }

    public function visitLiteral(\Graphpinator\Parser\Value\Literal $literal) : \Graphpinator\Value\InputedValue
    {
        return $this->type->createInputedValue($literal->getRawValue());
    }

    public function visitEnumLiteral(\Graphpinator\Parser\Value\EnumLiteral $enumLiteral) : \Graphpinator\Value\InputedValue
    {
        if ($this->type instanceof \Graphpinator\Type\NotNullType) {
            $this->type = $this->type->getInnerType();

            return $enumLiteral->accept($this);
        }

        if ($this->type instanceof \Graphpinator\Type\EnumType) {
            return $this->type->createInputedValue($enumLiteral->getRawValue());
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

            throw new \Graphpinator\Exception\Normalizer\UnknownInputField($name, $this->type->getName());
        }

        $inner = new \stdClass();

        foreach ($this->type->getArguments() as $argument) {
            $this->path->add($argument->getName() . ' <input field>');
            $inner->{$argument->getName()} = \property_exists($objectVal->getValue(), $argument->getName())
                ? self::convertArgumentValue($objectVal->getValue()->{$argument->getName()}, $argument, $this->variableSet, $this->path)
                : \Graphpinator\Value\ArgumentValue::fromRaw($argument, null);
            $this->path->pop();
        }

        return new \Graphpinator\Value\InputValue($this->type, $inner);
    }

    public function visitVariableRef(\Graphpinator\Parser\Value\VariableRef $variableRef) : \Graphpinator\Value\VariableValue
    {
        return $this->variableSet->offsetExists($variableRef->getVarName())
            ? new \Graphpinator\Value\VariableValue($this->type, $this->variableSet->offsetGet($variableRef->getVarName()))
            : throw new \Graphpinator\Exception\Normalizer\UnknownVariable($variableRef->getVarName());
    }
}
