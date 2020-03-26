<?php

declare(strict_types = 1);

namespace PGQL\Type\Scalar;

abstract class ScalarType extends \PGQL\Type\ConcreteDefinition implements \PGQL\Type\Inputable, \PGQL\Type\Outputable
{
    public function resolveFields(?array $requestedFields, \PGQL\Field\ResolveResult $parentValue) : \PGQL\Value\ValidatedValue
    {
        if (\is_array($requestedFields)) {
            throw new \Exception('Cannot require fields on leaf type.');
        }

        return $parentValue->getResult();
    }

    public function applyDefaults($value)
    {
        return $value;
    }

    public static function Int() : IntType
    {
        return new IntType();
    }

    public static function Float() : FloatType
    {
        return new FloatType();
    }

    public static function String() : StringType
    {
        return new StringType();
    }

    public static function Boolean() : BooleanType
    {
        return new BooleanType();
    }

    public static function ID() : IdType
    {
        return new IdType();
    }
}
