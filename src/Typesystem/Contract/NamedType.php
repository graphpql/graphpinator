<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Contract;

abstract class NamedType implements \Graphpinator\Typesystem\Contract\Type, \Graphpinator\Typesystem\Contract\Entity
{
    protected const NAME = '';
    protected const DESCRIPTION = null;

    abstract public function isInstanceOf(\Graphpinator\Typesystem\Contract\Type $type) : bool;

    final public function getName() : string
    {
        return static::NAME;
    }

    final public function printName() : string
    {
        return $this->getName();
    }

    final public function getDescription() : ?string
    {
        return static::DESCRIPTION;
    }

    final public function getNamedType() : \Graphpinator\Type\Contract\NamedType
    {
        return $this;
    }

    final public function getShapingType() : \Graphpinator\Typesystem\Contract\Type
    {
        return $this;
    }

    final public function isInputable() : bool
    {
        return $this instanceof Inputable;
    }

    final public function notNull() : \Graphpinator\Typesystem\NotNullType
    {
        return new \Graphpinator\Type\NotNullType($this);
    }

    final public function notNullList() : \Graphpinator\Typesystem\NotNullType
    {
        return new \Graphpinator\Type\NotNullType(new \Graphpinator\Typesystem\ListType(new \Graphpinator\Typesystem\NotNullType($this)));
    }

    final public function list() : \Graphpinator\Type\ListType
    {
        return new \Graphpinator\Type\ListType($this);
    }

    abstract public function accept(\Graphpinator\Typesystem\Contract\NamedTypeVisitor $visitor) : mixed;
}
