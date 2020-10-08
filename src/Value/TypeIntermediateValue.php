<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

final class TypeIntermediateValue implements ResolvedValue
{
    use \Nette\SmartObject;

    private \Graphpinator\Type\Type $type;
    private $rawValue;

    public function __construct(\Graphpinator\Type\Type $type, $rawValue)
    {
        $type->validateResolvedValue($rawValue);

        $this->type = $type;
        $this->rawValue = $rawValue;
    }

    public function getRawValue()
    {
        return $this->rawValue;
    }

    public function getType() : \Graphpinator\Type\Type
    {
        return $this->type;
    }
}
