<?php

declare(strict_types = 1);

namespace Graphpinator\Type;

final class ListType extends \Graphpinator\Type\Contract\ModifierDefinition
{
    public function createValue($rawValue) : \Graphpinator\Resolver\Value\ValidatedValue
    {
        return \Graphpinator\Resolver\Value\ListValue::create($rawValue, $this);
    }

    public function resolve(?\Graphpinator\Normalizer\FieldSet $requestedFields, \Graphpinator\Resolver\FieldResult $parentResult) : array
    {
        $listValue = $parentResult->getResult();

        if (!$listValue instanceof \Graphpinator\Resolver\Value\ListValue) {
            throw new \Graphpinator\Exception\Type\FailedToCreateListValue();
        }

        $return = [];

        foreach ($listValue as $val) {
            $return[] = $val->getType()->resolve($requestedFields, \Graphpinator\Resolver\FieldResult::fromValidated($val));
        }

        return $return;
    }

    public function validateValue($rawValue) : void
    {
        if ($rawValue === null || $rawValue instanceof \Graphpinator\Resolver\Value\ValidatedValue) {
            return;
        }

        if (!\is_iterable($rawValue)) {
            throw new \Graphpinator\Exception\Type\ExpectedListOrNull();
        }

        foreach ($rawValue as $val) {
            $this->innerType->validateValue($val);
        }
    }

    public function applyDefaults($value) : array
    {
        if (!\is_iterable($value)) {
            throw new \Graphpinator\Exception\Type\ExpectedList();
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

    public function printName() : string
    {
        return '[' . $this->innerType->printName() . ']';
    }
}
