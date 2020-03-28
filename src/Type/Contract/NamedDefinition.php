<?php

declare(strict_types = 1);

namespace PGQL\Type\Contract;

abstract class NamedDefinition implements \PGQL\Type\Contract\Definition
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

    public function validateValue($rawValue) : void
    {
        if ($rawValue === null) {
            return;
        }

        $this->validateNonNullValue($rawValue);
    }

    public function getNamedType() : \PGQL\Type\Contract\NamedDefinition
    {
        return $this;
    }

    public function isInstanceOf(\PGQL\Type\Contract\Definition $type): bool
    {
        if ($type instanceof \PGQL\Type\NotNullType) {
            return $this->isInstanceOf($type->getInnerType());
        }

        return $type instanceof static;
    }

    public function notNull() : \PGQL\Type\NotNullType
    {
        return new \PGQL\Type\NotNullType($this);
    }

    public function notNullList() : \PGQL\Type\NotNullType
    {
        return new \PGQL\Type\NotNullType(new \PGQL\Type\ListType(new \PGQL\Type\NotNullType($this)));
    }

    public function list() : \PGQL\Type\ListType
    {
        return new \PGQL\Type\ListType($this);
    }

    protected function validateNonNullValue($rawValue) : void
    {
    }
}
