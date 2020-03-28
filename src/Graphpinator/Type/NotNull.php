<?php

declare(strict_types = 1);

namespace PGQL\Type;

final class NotNull extends ModifierDefinition implements Inputable, Outputable
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
        if ($rawValue === null) {
            throw new \Exception('Value cannot be null.');
        }

        return $this->innerType->createValue($rawValue);
    }

    public function resolveFields(?array $requestedFields, \PGQL\Field\ResolveResult $parentValue)
    {
        return $this->innerType->resolveFields($requestedFields, $parentValue);
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
