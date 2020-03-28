<?php

declare(strict_types = 1);

namespace PGQL\Type;

final class NotNullType extends \PGQL\Type\Contract\ModifierDefinition
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
        if (!$this->innerType instanceof \PGQL\Type\Contract\Inputable) {
            return $value;
        }

        return $this->innerType->applyDefaults($value);
    }

    public function isInstanceOf(\PGQL\Type\Contract\Definition $type): bool
    {
        if ($type instanceof self) {
            return $this->innerType->isInstanceOf($type->getInnerType());
        }

        return false;
    }
}
