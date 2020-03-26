<?php

declare(strict_types = 1);

namespace PGQL\Type;

final class ListType extends ModifierDefinition implements Inputable, Outputable
{
    public function validateValue($rawValue) : void
    {
        if ($rawValue === null) {
            return;
        }

        if (\is_iterable($rawValue)) {
            foreach ($rawValue as $val) {
                $this->innerType->validateValue($val);
            }

            return;
        }

        throw new \Exception('Value must be list or null.');
    }

    public function createValue($rawValue) : \PGQL\Value\ValidatedValue
    {
        if ($rawValue === null) {
            return new \PGQL\Value\ValidatedValue($rawValue, $this);
        }

        if (\is_iterable($rawValue)) {
            return new \PGQL\Value\ValidatedListValue($rawValue, $this->innerType);
        }

        throw new \Exception('Value must be list or null.');
    }

    public function resolveFields(?array $requestedFields, \PGQL\Field\ResolveResult $parentValue) : array
    {
        if ($requestedFields === null) {
            throw new \Exception('List without fields specified.');
        }

        if (!$parentValue->getResult() instanceof \PGQL\Value\ValidatedListValue) {
            throw new \Exception('Cannot create list');
        }

        $return = [];

        foreach ($parentValue->getResult() as $val) {
            $return[] = $this->innerType->resolveFields($requestedFields, \PGQL\Field\ResolveResult::fromValidated($val));
        }

        return $return;
    }

    public function applyDefaults($value)
    {
        if (!$this->innerType instanceof Inputable || $value === null) {
            return $value;
        }

        if (!\is_iterable($value)) {
            throw new \Exception('Value has to be list.');
        }

        $return = [];

        foreach ($value as $val) {
            $return[] = $this->innerType->applyDefaults($val);
        }

        return $return;
    }

    public function isInstanceOf(Definition $type): bool
    {
        if ($type instanceof self) {
            return $this->innerType->isInstanceOf($type->getInnerType());
        }

        if ($type instanceof NotNull) {
            return $this->isInstanceOf($type->getInnerType());
        }

        return false;
    }
}
