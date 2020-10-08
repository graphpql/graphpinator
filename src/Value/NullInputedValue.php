<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

final class NullInputedValue implements InputedValue, NullValue
{
    use \Nette\SmartObject;

    protected \Graphpinator\Type\Contract\Inputable $type;

    public function __construct(\Graphpinator\Type\Contract\Inputable $type)
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

    public function getType() : \Graphpinator\Type\Contract\Inputable
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
}
