<?php

declare(strict_types = 1);

namespace Infinityloop\Graphpinator\Type;

final class NotNullType extends \Infinityloop\Graphpinator\Type\Contract\ModifierDefinition
{
    public function createValue($rawValue) : \Infinityloop\Graphpinator\Value\ValidatedValue
    {
        $value = $this->innerType->createValue($rawValue);

        if ($value instanceof \Infinityloop\Graphpinator\Value\NullValue) {
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

    public function resolveFields(?\Infinityloop\Graphpinator\Parser\RequestFieldSet $requestedFields, \Infinityloop\Graphpinator\Field\ResolveResult $parent)
    {
        return $this->innerType->resolveFields($requestedFields, $parent);
    }

    public function applyDefaults($value)
    {
        return $this->innerType->applyDefaults($value);
    }

    public function isInstanceOf(\Infinityloop\Graphpinator\Type\Contract\Definition $type): bool
    {
        if ($type instanceof self) {
            return $this->innerType->isInstanceOf($type->getInnerType());
        }

        return false;
    }
}
