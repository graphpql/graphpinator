<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

final class TypeIntermediateValue implements \Graphpinator\Value\ResolvedValue
{
    use \Nette\SmartObject;

    private \Graphpinator\Type\Type $type;
    private $rawValue;

    public function __construct(\Graphpinator\Type\Type $type, $rawValue)
    {
        if (!$type->validateNonNullValue($rawValue)) {
            throw new \Graphpinator\Exception\Value\InvalidValue($type->getName(), $rawValue, false);
        }

        $this->type = $type;
        $this->rawValue = $rawValue;
    }

    /**
     * @return array|bool|float|int|\stdClass|string|null
     */
    public function getRawValue()
    {
        return $this->rawValue;
    }

    public function getType() : \Graphpinator\Type\Type
    {
        return $this->type;
    }
}
