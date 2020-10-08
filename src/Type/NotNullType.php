<?php

declare(strict_types = 1);

namespace Graphpinator\Type;

final class NotNullType extends \Graphpinator\Type\Contract\ModifierDefinition
{
    public function createValue($rawValue) : \Graphpinator\Resolver\Value\ValidatedValue
    {
        $value = $this->innerType->createValue($rawValue);

        if ($value instanceof \Graphpinator\Resolver\Value\NullValue) {
            throw new \Graphpinator\Exception\Value\ValueCannotBeNull();
        }

        return $value;
    }

    public function validateValue($rawValue) : void
    {
        if ($rawValue === null) {
            throw new \Graphpinator\Exception\Value\ValueCannotBeNull();
        }

        $this->innerType->validateValue($rawValue);
    }

    //@phpcs:ignore SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingAnyTypeHint
    public function resolve(?\Graphpinator\Normalizer\FieldSet $requestedFields, \Graphpinator\Resolver\FieldResult $parentResult)
    {
        return $this->innerType->resolve($requestedFields, $parentResult);
    }

    public function isInstanceOf(\Graphpinator\Type\Contract\Definition $type) : bool
    {
        if ($type instanceof self) {
            return $this->innerType->isInstanceOf($type->getInnerType());
        }

        return false;
    }

    public function getTypeKind() : string
    {
        return \Graphpinator\Type\Introspection\TypeKind::NON_NULL;
    }

    public function printName() : string
    {
        return $this->innerType->printName() . '!';
    }
}
