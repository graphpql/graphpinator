<?php

declare(strict_types = 1);

namespace Graphpinator\Type;

final class NotNullType extends \Graphpinator\Type\Contract\ModifierDefinition
{
    public function createValue($rawValue) : \Graphpinator\Value\ValidatedValue
    {
        $value = $this->innerType->createValue($rawValue);

        if ($value instanceof \Graphpinator\Value\NullValue) {
            throw new \Exception('Value cannot be null.');
        }

        return $value;
    }

    public function validateValue($rawValue) : void
    {
        if ($rawValue === null) {
            throw new \Exception('Value cannot be null.');
        }

        $this->innerType->validateValue($rawValue);
    }

    public function resolveFields(?\Graphpinator\Request\FieldSet $requestedFields, \Graphpinator\Request\ResolveResult $parentResult)
    {
        return $this->innerType->resolveFields($requestedFields, $parentResult);
    }

    public function applyDefaults($value)
    {
        return $this->innerType->applyDefaults($value);
    }

    public function isInstanceOf(\Graphpinator\Type\Contract\Definition $type): bool
    {
        if ($type instanceof self) {
            return $this->innerType->isInstanceOf($type->getInnerType());
        }

        return false;
    }
}
