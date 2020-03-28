<?php

declare(strict_types = 1);

namespace Infinityloop\Graphpinator\Type\Scalar;

abstract class ScalarType extends \Infinityloop\Graphpinator\Type\Contract\ConcreteDefinition implements
    \Infinityloop\Graphpinator\Type\Contract\Inputable,
    \Infinityloop\Graphpinator\Type\Contract\Resolvable
{
    use \Infinityloop\Graphpinator\Type\Contract\TResolvable;

    public function resolveFields(?\Infinityloop\Graphpinator\Parser\RequestFieldSet $requestedFields, \Infinityloop\Graphpinator\Field\ResolveResult $parent) : \Infinityloop\Graphpinator\Value\ValidatedValue
    {
        if ($requestedFields instanceof \Infinityloop\Graphpinator\Parser\RequestFieldSet) {
            throw new \Exception('Cannot require fields on leaf type.');
        }

        return $parent->getResult();
    }

    public function applyDefaults($value)
    {
        return $value;
    }

    public function createValue($rawValue) : \Infinityloop\Graphpinator\Value\ValidatedValue
    {
        return \Infinityloop\Graphpinator\Value\ScalarValue::create($rawValue, $this);
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
