<?php

declare(strict_types = 1);

namespace PGQL\Type\Scalar;

abstract class ScalarType extends \PGQL\Type\Contract\ConcreteDefinition implements
    \PGQL\Type\Contract\Inputable,
    \PGQL\Type\Contract\Outputable,
    \PGQL\Type\Contract\Resolvable
{
    public function resolveFields(?\PGQL\Parser\RequestFieldSet $requestedFields, \PGQL\Field\ResolveResult $parent) : \PGQL\Value\ValidatedValue
    {
        if ($requestedFields instanceof \PGQL\Parser\RequestFieldSet) {
            throw new \Exception('Cannot require fields on leaf type.');
        }

        return $parent->getResult();
    }

    public function applyDefaults($value)
    {
        return $value;
    }

    public function createValue($rawValue) : \PGQL\Value\ValidatedValue
    {
        return \PGQL\Value\ScalarValue::create($rawValue, $this);
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
