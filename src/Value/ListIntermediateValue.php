<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

final class ListIntermediateValue implements \Graphpinator\Value\ResolvedValue
{
    use \Nette\SmartObject;

    public function __construct(private \Graphpinator\Type\ListType $type, private iterable $rawValue)
    {
    }

    public function getRawValue(bool $forResolvers = false) : iterable
    {
        return $this->rawValue;
    }

    public function getType() : \Graphpinator\Type\ListType
    {
        return $this->type;
    }
}
