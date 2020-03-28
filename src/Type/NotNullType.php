<?php

declare(strict_types = 1);

namespace PGQL\Type;

use PGQL\Type\Contract\Definition;
use PGQL\Type\Contract\Inputable;
use PGQL\Type\Contract\ModifierDefinition;
use PGQL\Type\Contract\Outputable;

final class NotNullType extends ModifierDefinition implements Inputable, Outputable
{
    public function validateValue($rawValue) : void
    {
        if ($rawValue === null) {
            throw new \Exception('Value cannot be null.');
        }

        $this->innerType->validateValue($rawValue);
    }

    public function createValue($rawValue) : \PGQL\Value\ValidatedValue
    {
        $value = $this->innerType->createValue($rawValue);

        if ($value instanceof \PGQL\Value\NullValue) {
            throw new \Exception('Value cannot be null.');
        }

        return $value;
    }

    public function resolveFields(?\PGQL\Parser\RequestFieldSet $requestedFields, \PGQL\Field\ResolveResult $parent)
    {
        return $this->innerType->resolveFields($requestedFields, $parent);
    }

    public function applyDefaults($value)
    {
        if (!$this->innerType instanceof Inputable) {
            return $value;
        }

        return $this->innerType->applyDefaults($value);
    }

    public function isInstanceOf(Definition $type): bool
    {
        if ($type instanceof self) {
            return $this->innerType->isInstanceOf($type->getInnerType());
        }

        return false;
    }
}
