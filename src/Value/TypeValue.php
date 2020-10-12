<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

final class TypeValue implements \Graphpinator\Value\OutputValue
{
    use \Nette\SmartObject;

    private \Graphpinator\Type\Type $type;
    private \stdClass $value;

    public function __construct(\Graphpinator\Type\Type $type, \stdClass $rawValue)
    {
        $this->type = $type;
        $this->value = $rawValue;

        $type->validateConstraints($this);
    }

    public function getRawValue() : \stdClass
    {
        return $this->value;
    }

    public function getType() : \Graphpinator\Type\Type
    {
        return $this->type;
    }

    public function jsonSerialize() : \stdClass
    {
        return $this->value;
    }
}
