<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Contract;

abstract class NamedDefinition implements \Graphpinator\Type\Contract\Definition
{
    protected const NAME = '';
    protected const DESCRIPTION = null;

    abstract public function printSchema() : string;

    public function getName() : string
    {
        return static::NAME;
    }

    public function printName() : string
    {
        return $this->getName();
    }

    public function getDescription() : ?string
    {
        return static::DESCRIPTION;
    }

    public function getNamedType() : \Graphpinator\Type\Contract\NamedDefinition
    {
        return $this;
    }

    public function isInstanceOf(\Graphpinator\Type\Contract\Definition $type) : bool
    {
        if ($type instanceof \Graphpinator\Type\NotNullType) {
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

    public function isResolvable() : bool
    {
        return $this instanceof Resolvable;
    }

    public function notNull() : \Graphpinator\Type\NotNullType
    {
        return new \Graphpinator\Type\NotNullType($this);
    }

    public function notNullList() : \Graphpinator\Type\NotNullType
    {
        return new \Graphpinator\Type\NotNullType(new \Graphpinator\Type\ListType(new \Graphpinator\Type\NotNullType($this)));
    }

    public function list() : \Graphpinator\Type\ListType
    {
        return new \Graphpinator\Type\ListType($this);
    }

    protected function validateNonNullValue($rawValue) : bool
    {
        return true;
    }

    protected function printDescription() : string
    {
        if ($this->getDescription() === null) {
            return '';
        }

        return '"""' . \PHP_EOL . $this->getDescription() . \PHP_EOL . '"""' . \PHP_EOL;
    }
}
