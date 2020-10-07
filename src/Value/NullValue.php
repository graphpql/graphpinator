<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

final class NullValue implements InputableValue, ResolvableValue
{
    use \Nette\SmartObject;

    protected \Graphpinator\Type\Contract\Definition $type;
    protected $value;

    public function __construct(\Graphpinator\Type\Contract\Definition $type)
    {
        if ($type instanceof \Graphpinator\Type\NotNullType) {
            throw new \Graphpinator\Exception\Value\ValueCannotBeNull();
        }

        $this->type = $type;
    }

    /** @return null */
    public function getRawValue()
    {
        return null;
    }

    public function getType() : \Graphpinator\Type\Contract\LeafDefinition
    {
        return $this->type;
    }

    /** @return null */
    public function jsonSerialize()
    {
        return null;
    }

    public function printValue() : string
    {
        return 'null';
    }

    public function isNull() : bool
    {
        return true;
    }
}
