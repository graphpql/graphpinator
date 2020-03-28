<?php

declare(strict_types = 1);

namespace Infinityloop\Graphpinator\Type;

use Infinityloop\Graphpinator\Type\Contract\AbstractDefinition;
use Infinityloop\Graphpinator\Type\Contract\Definition;
use Infinityloop\Graphpinator\Type\Contract\Outputable;

abstract class InterfaceType extends AbstractDefinition implements Outputable, \Infinityloop\Graphpinator\Type\Utils\FieldContainer, \Infinityloop\Graphpinator\Type\Utils\InterfaceImplementor
{
    use \Infinityloop\Graphpinator\Type\Utils\TFieldContainer;
    use \Infinityloop\Graphpinator\Type\Utils\TInterfaceImplementor;

    public function __construct(\Infinityloop\Graphpinator\Field\FieldSet $fields, ?\Infinityloop\Graphpinator\Type\Utils\InterfaceSet $implements = null)
    {
        $this->fields = $fields;
        $this->implements = $implements instanceof \Infinityloop\Graphpinator\Type\Utils\InterfaceSet
            ? $implements
            : new \Infinityloop\Graphpinator\Type\Utils\InterfaceSet([]);
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

        return $type instanceof \Infinityloop\Graphpinator\Type\Utils\InterfaceImplementor
            && $type->implements($this);
    }
}
