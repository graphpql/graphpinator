<?php

declare(strict_types = 1);

namespace Graphpinator\Type;

final class ListType extends \Graphpinator\Type\Contract\ModifierDefinition
{
    public function createInputedValue($rawValue) : \Graphpinator\Value\InputedValue
    {
        if (\is_array($rawValue)) {
            return new \Graphpinator\Value\ListInputedValue($this, $rawValue);
        }

        return new \Graphpinator\Value\NullInputedValue($this);
    }

    public function createResolvedValue(mixed $rawValue) : \Graphpinator\Value\ResolvedValue
    {
        if (\is_iterable($rawValue)) {
            return new \Graphpinator\Value\ListIntermediateValue($this, $rawValue);
        }

        return new \Graphpinator\Value\NullResolvedValue($this);
    }

    public function resolve(
        ?\Graphpinator\Normalizer\Field\FieldSet $requestedFields,
        \Graphpinator\Value\ResolvedValue $parentResult
    ) : \Graphpinator\Value\ListResolvedValue
    {
        \assert($parentResult instanceof \Graphpinator\Value\ListIntermediateValue);
        \assert($this->innerType instanceof \Graphpinator\Type\Contract\Outputable);

        $return = [];

        foreach ($parentResult->getRawValue() as $rawValue) {
            $value = $this->innerType->createResolvedValue($rawValue);

            $return[] = $value->getType()->resolve($requestedFields, $value);
        }

        return new \Graphpinator\Value\ListResolvedValue($this, $return);
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

    public function getTypeKind() : string
    {
        return \Graphpinator\Type\Introspection\TypeKind::LIST;
    }

    public function printName() : string
    {
        return '[' . $this->innerType->printName() . ']';
    }

    public function notNull() : \Graphpinator\Type\NotNullType
    {
        return new \Graphpinator\Type\NotNullType($this);
    }
}
