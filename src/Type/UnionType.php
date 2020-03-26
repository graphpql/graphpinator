<?php

declare(strict_types = 1);

namespace PGQL\Type;

abstract class UnionType extends AbstractDefinition implements Outputable
{
    protected \PGQL\Type\Utils\ConcreteSet $types;

    public function __construct(\PGQL\Type\Utils\ConcreteSet $types)
    {
        $this->types = $types;
    }

    public function isInstanceOf(Definition $type) : bool
    {
        if ($type instanceof NotNull) {
            return $this->isInstanceOf($type->getInnerType());
        }

        return $type instanceof static && $this->getName() === $type->getName();
    }

    public function isImplementedBy(Definition $type) : bool
    {
        foreach ($this->types as $temp) {
            if ($type->isInstanceOf($temp)) {
                return true;
            }
        }

        return false;
    }
}
