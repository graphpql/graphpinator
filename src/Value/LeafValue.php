<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

final class LeafValue implements \Graphpinator\Value\InputedValue, \Graphpinator\Value\OutputValue
{
    use \Nette\SmartObject;

    private \Graphpinator\Type\Contract\LeafDefinition $type;
    private $rawValue;

    public function __construct(\Graphpinator\Type\Contract\LeafDefinition $type, $rawValue, bool $inputed)
    {
        if (!$type->validateNonNullValue($rawValue)) {
            throw new \Graphpinator\Exception\Value\InvalidValue($type->getName(), $rawValue, $inputed);
        }

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

    public function printValue() : string
    {
        return \json_encode($this->rawValue, \JSON_THROW_ON_ERROR);
    }

    public function prettyPrint(int $indentLevel) : string
    {
        return $this->printValue();
    }
}
