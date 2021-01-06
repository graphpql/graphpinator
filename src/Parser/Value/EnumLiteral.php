<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\Value;

final class EnumLiteral implements \Graphpinator\Parser\Value\Value
{
    use \Nette\SmartObject;

    public function __construct(
        private string $value
    ) {}

    public function getRawValue() : string
    {
        return $this->value;
    }

    public function createInputedValue(
        \Graphpinator\Type\Contract\Inputable $type,
        \Graphpinator\Normalizer\Variable\VariableSet $variableSet,
    ) : \Graphpinator\Value\InputedValue
    {
        if ($type instanceof \Graphpinator\Type\NotNullType) {
            return $this->createInputedValue($type->getInnerType(), $variableSet);
        }

        if ($type instanceof \Graphpinator\Type\EnumType) {
            return $type->createInputedValue($this->value);
        }

        throw new \Graphpinator\Exception\Value\InvalidValue($type->printName(), $this->value, true);
    }
}
