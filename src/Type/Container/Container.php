<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Container;

abstract class Container
{
    /**
     * Core function to find type by its name.
     */
    abstract public function getType(string $name) : \Graphpinator\Type\Contract\NamedDefinition;

    /**
     * Built-in Int type.
     */
    public static function Int() : \Graphpinator\Type\Scalar\IntType
    {
        return new \Graphpinator\Type\Scalar\IntType();
    }

    /**
     * Built-in Float type.
     */
    public static function Float() : \Graphpinator\Type\Scalar\FloatType
    {
        return new \Graphpinator\Type\Scalar\FloatType();
    }

    /**
     * Built-in String type.
     */
    public static function String() : \Graphpinator\Type\Scalar\StringType
    {
        return new \Graphpinator\Type\Scalar\StringType();
    }

    /**
     * Built-in Boolean type.
     */
    public static function Boolean() : \Graphpinator\Type\Scalar\BooleanType
    {
        return new \Graphpinator\Type\Scalar\BooleanType();
    }

    /**
     * Built-in ID type.
     */
    public static function ID() : \Graphpinator\Type\Scalar\IdType
    {
        return new \Graphpinator\Type\Scalar\IdType();
    }

    /**
     * Built-in __Schema type.
     */
    public static function introspectionSchema() : \Graphpinator\Type\Introspection\Schema
    {
        return new \Graphpinator\Type\Introspection\Schema();
    }

    /**
     * Built-in __Type type.
     */
    public static function introspectionType() : \Graphpinator\Type\Introspection\Type
    {
        return new \Graphpinator\Type\Introspection\Type();
    }

    /**
     * Built-in __TypeKind type.
     */
    public static function introspectionTypeKind() : \Graphpinator\Type\Introspection\TypeKind
    {
        return new \Graphpinator\Type\Introspection\TypeKind();
    }

    /**
     * Built-in __Field type.
     */
    public static function introspectionField() : \Graphpinator\Type\Introspection\Field
    {
        return new \Graphpinator\Type\Introspection\Field();
    }

    /**
     * Built-in __InputValue type.
     */
    public static function introspectionInputValue() : \Graphpinator\Type\Introspection\InputValue
    {
        return new \Graphpinator\Type\Introspection\InputValue();
    }

    /**
     * Built-in __EnumValue type.
     */
    public static function introspectionEnumValue() : \Graphpinator\Type\Introspection\EnumValue
    {
        return new \Graphpinator\Type\Introspection\EnumValue();
    }

    /**
     * Built-in __Directive type.
     */
    public static function introspectionDirective() : \Graphpinator\Type\Introspection\Directive
    {
        return new \Graphpinator\Type\Introspection\Directive();
    }

    /**
     * Built-in __DirectiveLocation enum.
     */
    public static function introspectionDirectiveLocation() : \Graphpinator\Type\Introspection\DirectiveLocation
    {
        return new \Graphpinator\Type\Introspection\DirectiveLocation();
    }
}
