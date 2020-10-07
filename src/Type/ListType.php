<?php

declare(strict_types = 1);

namespace Graphpinator\Type;

final class ListType extends \Graphpinator\Type\Contract\ModifierDefinition
{
    public function createInputableValue($rawValue) : \Graphpinator\Value\InputableValue
    {
        if ($rawValue === null) {
            return new \Graphpinator\Value\NullValue($this);
        }

        return new \Graphpinator\Value\InputableListValue($this, $rawValue);
    }

    public function createResolvableValue($rawValue) : \Graphpinator\Value\ResolvableValue
    {
        return new \Graphpinator\Value\ResolvableListValue($this, $rawValue);
    }

    public function resolve(
        ?\Graphpinator\Normalizer\FieldSet $requestedFields,
        \Graphpinator\Value\FieldValue $parentResult
    ) : \Graphpinator\Value\ResolvableListValue
    {
        $listValue = $parentResult->getValue();

        if (!$listValue instanceof \Graphpinator\Value\ResolvableListValue) {
            throw new \Exception('Cannot create list');
        }

        $return = [];

        foreach ($listValue as $val) {
            $return[] = $val->getType()->resolve($requestedFields, \Graphpinator\Resolver\FieldResult::fromValidated($val));
        }

        return new \Graphpinator\Value\ResolvableListValue($this, $return);
    }

    public function validateResolvedValue($rawValue) : void
    {
        if ($rawValue === null) {
            return;
        }

        if (!\is_iterable($rawValue)) {
            throw new \Exception('Value must be list or null.');
        }

        foreach ($rawValue as $val) {
            $this->innerType->validateResolvedValue($val);
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
