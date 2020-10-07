<?php

declare(strict_types = 1);

namespace Graphpinator\Type;

final class NotNullType extends \Graphpinator\Type\Contract\ModifierDefinition
{
    public function createInputableValue($rawValue) : \Graphpinator\Value\InputableListValue
    {
        return $this->innerType->createInputableValue($rawValue);
    }

    public function createResolvableValue($rawValue) : \Graphpinator\Value\ResolvableValue
    {
        return $this->innerType->createResolvableValue($rawValue);
    }

    public function validateResolvedValue($rawValue) : void
    {
        if ($rawValue === null) {
            throw new \Graphpinator\Exception\Value\ValueCannotBeNull();
        }

        $this->innerType->validateResolvedValue($rawValue);
    }

    public function resolve(
        ?\Graphpinator\Normalizer\FieldSet $requestedFields,
        \Graphpinator\Value\FieldValue $parentResult
    ) : \Graphpinator\Value\ResolvableValue
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
