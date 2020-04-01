<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Scalar;

abstract class ScalarType extends \Graphpinator\Type\Contract\ConcreteDefinition implements
    \Graphpinator\Type\Contract\Inputable,
    \Graphpinator\Type\Contract\Resolvable
{
    use \Graphpinator\Type\Contract\TResolvable;

    public function resolveFields(?\Graphpinator\Request\FieldSet $requestedFields, \Graphpinator\Field\ResolveResult $parent) : \Graphpinator\Value\ValidatedValue
    {
        if ($requestedFields instanceof \Graphpinator\Request\FieldSet) {
            throw new \Exception('Cannot require fields on leaf type.');
        }

        return $parent->getResult();
    }

    public function applyDefaults($value)
    {
        return $value;
    }

    public function createValue($rawValue) : \Graphpinator\Value\ValidatedValue
    {
        return \Graphpinator\Value\ScalarValue::create($rawValue, $this);
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
