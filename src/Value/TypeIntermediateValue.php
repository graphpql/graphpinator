<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

final class TypeIntermediateValue implements \Graphpinator\Value\ResolvedValue
{
    use \Nette\SmartObject;

    private \Graphpinator\Type\Type $type;
    private mixed $rawValue;

    public function __construct(\Graphpinator\Type\Type $type, mixed $rawValue)
    {
        $type->validateResolvedValue($rawValue);

        $this->type = $type;
        $this->rawValue = $rawValue;
    }

    public function getRawValue() : mixed
    {
        return $this->rawValue;
    }

    public function getType() : \Graphpinator\Type\Type
    {
        return $this->type;
    }
}
