<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

final class NullInputedValue implements \Graphpinator\Value\InputedValue, \Graphpinator\Value\NullValue
{
    use \Nette\SmartObject;

    private \Graphpinator\Type\Contract\Inputable $type;

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

    public function printValue(bool $prettyPrint = false, int $indentLevel = 1) : string
    {
        return 'null';
    }
}
