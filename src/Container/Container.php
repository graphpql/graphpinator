<?php

declare(strict_types = 1);

namespace Graphpinator\Container;

/**
 * Class Container which is responsible for fetching instances of type classes.
 * @phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
 */
abstract class Container
{
    use \Nette\SmartObject;

    protected static array $builtInTypes = [];
    protected static array $builtInDirectives = [];

    /**
     * Core function to find type by its name.
     * @param string $name
     */
    abstract public function getType(string $name) : ?\Graphpinator\Type\Contract\NamedDefinition;

    /**
     * Function to return all user-defined types.
     * @param bool $includeBuiltIn
     */
    abstract public function getTypes(bool $includeBuiltIn = false) : array;

    /**
     * Core function to find directive by its name.
     * @param string $name
     */
    abstract public function getDirective(string $name) : ?\Graphpinator\Directive\Directive;

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
        if (!\array_key_exists('Int', self::$builtInTypes)) {
            self::$builtInTypes['Int'] = new \Graphpinator\Type\Scalar\IntType();
        }

        return self::$builtInTypes['Int'];
    }

    /**
     * Built-in Float type.
     */
    public static function Float() : \Graphpinator\Type\Scalar\FloatType
    {
        if (!\array_key_exists('Float', self::$builtInTypes)) {
            self::$builtInTypes['Float'] = new \Graphpinator\Type\Scalar\FloatType();
        }

        return self::$builtInTypes['Float'];
    }

    /**
     * Built-in String type.
     */
    public static function String() : \Graphpinator\Type\Scalar\StringType
    {
        if (!\array_key_exists('String', self::$builtInTypes)) {
            self::$builtInTypes['String'] = new \Graphpinator\Type\Scalar\StringType();
        }

        return self::$builtInTypes['String'];
    }

    /**
     * Built-in Boolean type.
     */
    public static function Boolean() : \Graphpinator\Type\Scalar\BooleanType
    {
        if (!\array_key_exists('Boolean', self::$builtInTypes)) {
            self::$builtInTypes['Boolean'] = new \Graphpinator\Type\Scalar\BooleanType();
        }

        return self::$builtInTypes['Boolean'];
    }

    /**
     * Built-in ID type.
     */
    public static function ID() : \Graphpinator\Type\Scalar\IdType
    {
        if (!\array_key_exists('ID', self::$builtInTypes)) {
            self::$builtInTypes['ID'] = new \Graphpinator\Type\Scalar\IdType();
        }

        return self::$builtInTypes['ID'];
    }

    /**
     * Built-in Skip directive.
     */
    public static function directiveSkip() : \Graphpinator\Directive\Spec\SkipDirective
    {
        if (!\array_key_exists('skip', self::$builtInDirectives)) {
            self::$builtInDirectives['skip'] = new \Graphpinator\Directive\Spec\SkipDirective();
        }

        return self::$builtInDirectives['skip'];
    }

    /**
     * Built-in Include directive.
     */
    public static function directiveInclude() : \Graphpinator\Directive\Spec\IncludeDirective
    {
        if (!\array_key_exists('include', self::$builtInDirectives)) {
            self::$builtInDirectives['include'] = new \Graphpinator\Directive\Spec\IncludeDirective();
        }

        return self::$builtInDirectives['include'];
    }

    /**
     * Built-in Deprecated directive.
     */
    public static function directiveDeprecated() : \Graphpinator\Directive\Spec\DeprecatedDirective
    {
        if (!\array_key_exists('deprecated', self::$builtInDirectives)) {
            self::$builtInDirectives['deprecated'] = new \Graphpinator\Directive\Spec\DeprecatedDirective();
        }

        return self::$builtInDirectives['deprecated'];
    }
}
//@phpcs:enable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
