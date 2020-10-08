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
            throw new \Exception('Cannot create list');
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
            throw new \Exception('Value must be list or null.');
        }

        foreach ($rawValue as $val) {
            $this->innerType->validateValue($val);
        }
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
