<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

final class LeafValue implements InputedValue, OutputValue
{
    use \Nette\SmartObject;

    protected \Graphpinator\Type\Contract\LeafDefinition $type;
    protected $rawValue;

    public function __construct(\Graphpinator\Type\Contract\LeafDefinition $type, $rawValue)
    {
        $type->validateResolvedValue($rawValue);

        $this->type = $type;
        $this->rawValue = $rawValue;
    }

    /** @return string|int|float|bool */
    public function getRawValue()
    {
        return $this->rawValue;
    }

    public function getType() : \Graphpinator\Type\Contract\LeafDefinition
    {
        return $this->type;
    }

    /** @return string|int|float|bool */
    public function jsonSerialize()
    {
        return $this->rawValue;
    }

    public function printValue(bool $prettyPrint = false, int $indentLevel = 1) : string
    {
        return \json_encode($this->rawValue, \JSON_THROW_ON_ERROR);
    }
}
