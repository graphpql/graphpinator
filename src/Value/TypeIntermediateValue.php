<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

final class TypeIntermediateValue implements \Graphpinator\Value\ResolvedValue
{
    use \Nette\SmartObject;

    public function __construct(private \Graphpinator\Typesystem\Type $type, private mixed $rawValue)
    {
        if (!$type->validateNonNullValue($rawValue)) {
            throw new \Graphpinator\Exception\Value\InvalidValue($type->getName(), $rawValue, false);
        }
    }

    public function getRawValue(bool $forResolvers = false) : mixed
    {
        return $this->rawValue;
    }

    public function getType() : \Graphpinator\Typesystem\Type
    {
        return $this->type;
    }
}
