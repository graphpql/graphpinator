<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

final class ListIntermediateValue implements \Graphpinator\Value\ResolvedValue
{
    use \Nette\SmartObject;

    private \Graphpinator\Type\ListType $type;
    private iterable $rawValue;

    public function __construct(\Graphpinator\Type\ListType $type, iterable $rawValue)
    {
        $this->type = $type;
        $this->rawValue = $rawValue;
    }

    public function getRawValue(bool $forResolvers = false) : mixed
    {
        return $this->rawValue;
    }

    public function getType() : \Graphpinator\Type\ListType
    {
        return $this->type;
    }
}
