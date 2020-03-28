<?php

declare(strict_types = 1);

namespace Infinityloop\Graphpinator\Type;

final class ListType extends \Infinityloop\Graphpinator\Type\Contract\ModifierDefinition
{
    public function createValue($rawValue) : \Infinityloop\Graphpinator\Value\ValidatedValue
    {
        return \Infinityloop\Graphpinator\Value\ListValue::create($rawValue, $this);
    }

    public function resolveFields(?\Infinityloop\Graphpinator\Parser\RequestFieldSet $requestedFields, \Infinityloop\Graphpinator\Field\ResolveResult $parent) : array
    {
        if ($requestedFields === null) {
            throw new \Exception('List without fields specified.');
        }

        if (!$parent->getResult() instanceof \Infinityloop\Graphpinator\Value\ListValue) {
            throw new \Exception('Cannot create list');
        }

        $return = [];

        foreach ($parent->getResult() as $val) {
            $return[] = $this->innerType->resolveFields($requestedFields, \Infinityloop\Graphpinator\Field\ResolveResult::fromValidated($val));
        }

        return $return;
    }

    public function validateValue($rawValue) : void
    {
        if ($rawValue === null || $rawValue instanceof \Infinityloop\Graphpinator\Value\ValidatedValue) {
            return;
        }

        if (!\is_iterable($rawValue)) {
            throw new \Exception('Value must be list or null.');
        }

        foreach ($rawValue as $val) {
            $this->innerType->validateValue($val);
        }
    }

    public function applyDefaults($value) : array
    {
        if (!\is_iterable($value)) {
            throw new \Exception('Value has to be list.');
        }

        $return = [];

        foreach ($value as $val) {
            $return[] = $this->innerType->applyDefaults($val);
        }

        return $return;
    }

    public function isInstanceOf(\Infinityloop\Graphpinator\Type\Contract\Definition $type) : bool
    {
        if ($type instanceof self) {
            return $this->innerType->isInstanceOf($type->getInnerType());
        }

        if ($type instanceof NotNullType) {
            return $this->isInstanceOf($type->getInnerType());
        }

        return false;
    }

    public function notNull() : \Infinityloop\Graphpinator\Type\NotNullType
    {
        return new \Infinityloop\Graphpinator\Type\NotNullType($this);
    }
}
