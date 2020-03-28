<?php

declare(strict_types = 1);

namespace Infinityloop\Graphpinator\Type\Contract;

abstract class NamedDefinition implements \Infinityloop\Graphpinator\Type\Contract\Definition
{
    protected const NAME = '';
    protected const DESCRIPTION = null;

    public function getName() : string
    {
        return static::NAME;
    }

    public function getDescription() : ?string
    {
        return static::DESCRIPTION;
    }

    public function getNamedType() : \Infinityloop\Graphpinator\Type\Contract\NamedDefinition
    {
        return $this;
    }

    public function isInstanceOf(\Infinityloop\Graphpinator\Type\Contract\Definition $type): bool
    {
        if ($type instanceof \Infinityloop\Graphpinator\Type\NotNullType) {
            return $this->isInstanceOf($type->getInnerType());
        }

        return $type instanceof static;
    }

    public function isInputable() : bool
    {
        return $this instanceof Inputable;
    }

    public function isOutputable() : bool
    {
        return $this instanceof Outputable;
    }

    public function isInstantiable() : bool
    {
        return $this instanceof Instantiable;
    }

    public function isResolvable() : bool
    {
        return $this instanceof Resolvable;
    }

    public function notNull() : \Infinityloop\Graphpinator\Type\NotNullType
    {
        return new \Infinityloop\Graphpinator\Type\NotNullType($this);
    }

    public function notNullList() : \Infinityloop\Graphpinator\Type\NotNullType
    {
        return new \Infinityloop\Graphpinator\Type\NotNullType(new \Infinityloop\Graphpinator\Type\ListType(new \Infinityloop\Graphpinator\Type\NotNullType($this)));
    }

    public function list() : \Infinityloop\Graphpinator\Type\ListType
    {
        return new \Infinityloop\Graphpinator\Type\ListType($this);
    }

    protected function validateNonNullValue($rawValue) : void
    {
    }
}
