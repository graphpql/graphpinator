<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

final class NullInputedValue implements \Graphpinator\Value\InputedValue, \Graphpinator\Value\NullValue
{
    use \Nette\SmartObject;

    private \Graphpinator\Type\Contract\Inputable $type;

    public function __construct(\Graphpinator\Type\Contract\Inputable $type)
    {
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

    public function printValue() : string
    {
        return 'null';
    }

    public function prettyPrint(int $indentLevel) : string
    {
        return $this->printValue();
    }
}
