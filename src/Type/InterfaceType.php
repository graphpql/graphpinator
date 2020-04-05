<?php

declare(strict_types = 1);

namespace Graphpinator\Type;

use Graphpinator\Type\Contract\AbstractDefinition;
use Graphpinator\Type\Contract\Definition;
use Graphpinator\Type\Contract\Outputable;

abstract class InterfaceType extends AbstractDefinition implements Outputable, \Graphpinator\Type\Utils\InterfaceImplementor
{
    use \Graphpinator\Type\Utils\TInterfaceImplementor;

    protected \Graphpinator\Field\FieldSet $fields;

    public function __construct(\Graphpinator\Field\FieldSet $fields, ?\Graphpinator\Type\Utils\InterfaceSet $implements = null)
    {
        $this->fields = $fields;
        $this->implements = $implements ?? new \Graphpinator\Type\Utils\InterfaceSet([]);

        $this->validateInterfaces();
    }

    public function isInstanceOf(Definition $type) : bool
    {
        if ($type instanceof NotNullType) {
            return $this->isInstanceOf($type->getInnerType());
        }

        return $type instanceof static
            || ($type instanceof self && $this->implements($type));
    }

    public function isImplementedBy(Definition $type) : bool
    {
        if ($type instanceof NotNullType) {
            return $this->isImplementedBy($type->getInnerType());
        }

        return $type instanceof \Graphpinator\Type\Utils\InterfaceImplementor
            && $type->implements($this);
    }

    public function getFields() : \Graphpinator\Field\FieldSet
    {
        return $this->fields;
    }
}
