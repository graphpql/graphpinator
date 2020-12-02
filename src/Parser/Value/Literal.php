<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\Value;

final class Literal implements \Graphpinator\Parser\Value\Value
{
    use \Nette\SmartObject;

    public function __construct(
        private string|int|float|bool|null $value
    ) {}

    public function getRawValue() : string|int|float|bool|null
    {
        return $this->value;
    }

    public function hasVariables() : bool
    {
        return false;
    }

    public function createInputedValue(
        \Graphpinator\Type\Contract\Inputable $type,
        \Graphpinator\Normalizer\Variable\VariableSet $variableSet,
    ) : \Graphpinator\Value\InputedValue
    {
        return $type->createInputedValue($this->value);
    }
}
