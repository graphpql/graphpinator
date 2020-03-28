<?php

declare(strict_types = 1);

namespace PGQL\Type;

abstract class UnionType extends \PGQL\Type\Contract\AbstractDefinition implements \PGQL\Type\Contract\Outputable
{
    protected \PGQL\Type\Utils\ConcreteSet $types;

    public function __construct(\PGQL\Type\Utils\ConcreteSet $types)
    {
        $this->types = $types;
    }

    public function isInstanceOf(\PGQL\Type\Contract\Definition $type) : bool
    {
        if ($type instanceof NotNullType) {
            return $this->isInstanceOf($type->getInnerType());
        }

        return $type instanceof static;
    }

    public function isImplementedBy(\PGQL\Type\Contract\Definition $type) : bool
    {
        foreach ($this->types as $temp) {
            if ($temp->isInstanceOf($type)) {
                return true;
            }
        }

        return false;
    }
}
