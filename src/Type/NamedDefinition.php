<?php

declare(strict_types = 1);

namespace PGQL\Type;

abstract class NamedDefinition implements Definition
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

    public function createValue($rawValue) : \PGQL\Value\ValidatedValue
    {
        return new \PGQL\Value\ValidatedValue($rawValue, $this);
    }

    public function validateValue($rawValue) : void
    {
        if ($rawValue === null) {
            return;
        }

        $this->validateNonNullValue($rawValue);
    }

    public function getNamedType() : NamedDefinition
    {
        return $this;
    }

    public function isInstanceOf(\PGQL\Type\Definition $type): bool
    {
        if ($type instanceof \PGQL\Type\NotNull) {
            return $this->isInstanceOf($type->getInnerType());
        }

        return $type instanceof static && $this->getName() === $type->getName();
    }

    public function notNull() : NotNull
    {
        return new NotNull($this);
    }

    public function notNullList() : NotNull
    {
        return new NotNull(new ListType(new NotNull($this)));
    }

    public function list() : ListType
    {
        return new ListType($this);
    }

    protected function validateNonNullValue($rawValue) : void
    {
    }
}
