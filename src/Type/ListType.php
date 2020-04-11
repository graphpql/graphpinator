<?php

declare(strict_types = 1);

namespace Graphpinator\Type;

final class ListType extends \Graphpinator\Type\Contract\ModifierDefinition
{
    public function createValue($rawValue) : \Graphpinator\Value\ValidatedValue
    {
        return \Graphpinator\Value\ListValue::create($rawValue, $this);
    }

    public function resolveFields(?\Graphpinator\Normalizer\FieldSet $requestedFields, \Graphpinator\Resolver\FieldResult $parentResult) : array
    {
        if (!$parentResult->getResult() instanceof \Graphpinator\Value\ListValue) {
            throw new \Exception('Cannot create list');
        }

        $return = [];

        foreach ($parentResult->getResult() as $val) {
            $return[] = $this->innerType->resolveFields($requestedFields, \Graphpinator\Resolver\FieldResult::fromValidated($val));
        }

        return $return;
    }

    public function validateValue($rawValue) : void
    {
        if ($rawValue === null || $rawValue instanceof \Graphpinator\Value\ValidatedValue) {
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

    public function isInstanceOf(\Graphpinator\Type\Contract\Definition $type) : bool
    {
        if ($type instanceof self) {
            return $this->innerType->isInstanceOf($type->getInnerType());
        }

        if ($type instanceof NotNullType) {
            return $this->isInstanceOf($type->getInnerType());
        }

        return false;
    }

    public function notNull() : \Graphpinator\Type\NotNullType
    {
        return new \Graphpinator\Type\NotNullType($this);
    }

    public function getTypeKind() : string
    {
        return \Graphpinator\Type\Introspection\TypeKind::LIST;
    }
}
