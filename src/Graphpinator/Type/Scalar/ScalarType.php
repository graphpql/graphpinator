<?php

declare(strict_types = 1);

namespace PGQL\Type\Scalar;

abstract class ScalarType extends \PGQL\Type\Contract\ConcreteDefinition implements
    \PGQL\Type\Contract\Inputable,
    \PGQL\Type\Contract\Resolvable
{
    use \PGQL\Type\Contract\TResolvable;

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

    public function validateValue($rawValue) : void
    {
        if ($rawValue === null) {
            return;
        }

        $this->validateNonNullValue($rawValue);
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
