<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Contract;

abstract class NamedDefinition implements \Graphpinator\Type\Contract\Definition, \Graphpinator\Typesystem\NamedType
{
    protected const NAME = '';
    protected const DESCRIPTION = null;

    abstract public function isInstanceOf(\Graphpinator\Type\Contract\Definition $type) : bool;

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

    final public function getNamedType() : \Graphpinator\Type\Contract\NamedDefinition
    {
        return $this;
    }

    final public function getShapingType() : \Graphpinator\Type\Contract\Definition
    {
        return $this;
    }

    final public function isInputable() : bool
    {
        return $this instanceof Inputable;
    }

    final public function notNull() : \Graphpinator\Type\NotNullType
    {
        return new \Graphpinator\Type\NotNullType($this);
    }

    final public function notNullList() : \Graphpinator\Type\NotNullType
    {
        return new \Graphpinator\Type\NotNullType(new \Graphpinator\Type\ListType(new \Graphpinator\Type\NotNullType($this)));
    }

    final public function list() : \Graphpinator\Type\ListType
    {
        return new \Graphpinator\Type\ListType($this);
    }
}
