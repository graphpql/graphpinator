<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Container;

/**
 * Class Container which is responsible for fetching instances of type classes.
 * @phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
 */
abstract class Container
{
    /**
     * Core function to find type by its name.
     * @param string $name
     */
    abstract public function getType(string $name) : \Graphpinator\Type\Contract\NamedDefinition;

    /**
     * Function to return all user-defined types.
     * @param bool $includeBuiltIn
     */
    abstract public function getTypes(bool $includeBuiltIn = false) : array;

    /**
     * Core function to find directive by its name.
     * @param string $name
     */
    abstract public function getDirective(string $name) : \Graphpinator\Directive\Directive;

    /**
     * Function to return all user-defined directives.
     * @param bool $includeBuiltIn
     */
    abstract public function getDirectives(bool $includeBuiltIn = false) : array;

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
     * Built-in Skip directive.
     */
    public static function directiveSkip() : \Graphpinator\Directive\SkipDirective
    {
        return new \Graphpinator\Directive\SkipDirective();
    }

    /**
     * Built-in Include directive.
     */
    public static function directiveInclude() : \Graphpinator\Directive\IncludeDirective
    {
        return new \Graphpinator\Directive\IncludeDirective();
    }

    /**
     * Built-in Deprecated directive.
     */
    public static function directiveDeprecated() : \Graphpinator\Directive\DeprecatedDirective
    {
        return new \Graphpinator\Directive\DeprecatedDirective();
    }

    /**
     * Graphpinator IntConstraint directive.
     */
    public static function directiveIntConstraint() : \Graphpinator\Directive\IntConstraintDirective
    {
        return new \Graphpinator\Directive\IntConstraintDirective();
    }

    /**
     * Graphpinator FloatConstraint directive.
     */
    public static function directiveFloatConstraint() : \Graphpinator\Directive\FloatConstraintDirective
    {
        return new \Graphpinator\Directive\FloatConstraintDirective();
    }

    /**
     * Graphpinator StringConstraint directive.
     */
    public static function directiveStringConstraint() : \Graphpinator\Directive\StringConstraintDirective
    {
        return new \Graphpinator\Directive\StringConstraintDirective();
    }

    /**
     * Graphpinator ListConstraint directive.
     */
    public static function directiveListConstraint() : \Graphpinator\Directive\ListConstraintDirective
    {
        return new \Graphpinator\Directive\ListConstraintDirective();
    }

    /**
     * Graphpinator ObjectConstraint directive.
     */
    public static function directiveInputConstraint() : \Graphpinator\Directive\InputConstraintDirective
    {
        return new \Graphpinator\Directive\InputConstraintDirective();
    }

    /**
     * Graphpinator ListConstraint input.
     */
    public static function listConstraintInput() : \Graphpinator\Constraint\ListConstraintInput
    {
        return new \Graphpinator\Constraint\ListConstraintInput();
    }

    /**
     * Built-in __Schema type.
     */
    public function introspectionSchema() : \Graphpinator\Type\Introspection\Schema
    {
        return new \Graphpinator\Type\Introspection\Schema($this);
    }

    /**
     * Built-in __Type type.
     */
    public function introspectionType() : \Graphpinator\Type\Introspection\Type
    {
        return new \Graphpinator\Type\Introspection\Type($this);
    }

    /**
     * Built-in __TypeKind type.
     */
    public function introspectionTypeKind() : \Graphpinator\Type\Introspection\TypeKind
    {
        return new \Graphpinator\Type\Introspection\TypeKind();
    }

    /**
     * Built-in __Field type.
     */
    public function introspectionField() : \Graphpinator\Type\Introspection\Field
    {
        return new \Graphpinator\Type\Introspection\Field($this);
    }

    /**
     * Built-in __InputValue type.
     */
    public function introspectionInputValue() : \Graphpinator\Type\Introspection\InputValue
    {
        return new \Graphpinator\Type\Introspection\InputValue($this);
    }

    /**
     * Built-in __EnumValue type.
     */
    public function introspectionEnumValue() : \Graphpinator\Type\Introspection\EnumValue
    {
        return new \Graphpinator\Type\Introspection\EnumValue();
    }

    /**
     * Built-in __Directive type.
     */
    public function introspectionDirective() : \Graphpinator\Type\Introspection\Directive
    {
        return new \Graphpinator\Type\Introspection\Directive($this);
    }

    /**
     * Built-in __DirectiveLocation enum.
     */
    public function introspectionDirectiveLocation() : \Graphpinator\Type\Introspection\DirectiveLocation
    {
        return new \Graphpinator\Type\Introspection\DirectiveLocation();
    }
}
//@phpcs:enable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
