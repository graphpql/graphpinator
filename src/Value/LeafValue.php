<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

final class LeafValue implements InputableValue, ResolvableValue
{
    use \Nette\SmartObject;

    protected \Graphpinator\Type\Contract\LeafDefinition $type;
    protected $value;

    public function __construct(\Graphpinator\Type\Contract\LeafDefinition $type, $rawValue)
    {
        $type->validateResolvedValue($rawValue);

        $this->type = $type;
        $this->value = $rawValue;
    }

    /** @return string|int|float|bool */
    public function getRawValue()
    {
        return $this->value;
    }

    public function getType(): \Graphpinator\Type\Contract\LeafDefinition
    {
        return $this->type;
    }

    /** @return string|int|float|bool */
    public function jsonSerialize()
    {
        return \json_encode($this->value, \JSON_THROW_ON_ERROR);
    }

    public function printValue() : string
    {
        return \json_encode($this->value, \JSON_THROW_ON_ERROR);
    }

    public function isNull() : bool
    {
        return false;
    }
}
