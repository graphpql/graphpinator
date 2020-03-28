<?php

declare(strict_types = 1);

namespace Infinityloop\Graphpinator\Type;

abstract class UnionType extends \Infinityloop\Graphpinator\Type\Contract\AbstractDefinition implements \Infinityloop\Graphpinator\Type\Contract\Outputable
{
    protected \Infinityloop\Graphpinator\Type\Utils\ConcreteSet $types;

    public function __construct(\Infinityloop\Graphpinator\Type\Utils\ConcreteSet $types)
    {
        $this->types = $types;
    }

    public function isInstanceOf(\Infinityloop\Graphpinator\Type\Contract\Definition $type) : bool
    {
        if ($type instanceof NotNullType) {
            return $this->isInstanceOf($type->getInnerType());
        }

        return $type instanceof static;
    }

    public function isImplementedBy(\Infinityloop\Graphpinator\Type\Contract\Definition $type) : bool
    {
        foreach ($this->types as $temp) {
            if ($temp->isInstanceOf($type)) {
                return true;
            }
        }

        return false;
    }
}
