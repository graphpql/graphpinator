<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem;

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
    abstract public function getType(string $name) : ?\Graphpinator\Typesystem\Contract\NamedType;

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
    public static function Int() : \Graphpinator\Type\Spec\IntType
    {
        if (!\array_key_exists('Int', self::$builtInTypes)) {
            self::$builtInTypes['Int'] = new \Graphpinator\Type\Spec\IntType();
        }

        return self::$builtInTypes['Int'];
    }

    /**
     * Built-in Float type.
     */
    public static function Float() : \Graphpinator\Type\Spec\FloatType
    {
        if (!\array_key_exists('Float', self::$builtInTypes)) {
            self::$builtInTypes['Float'] = new \Graphpinator\Type\Spec\FloatType();
        }

        return self::$builtInTypes['Float'];
    }

    /**
     * Built-in String type.
     */
    public static function String() : \Graphpinator\Type\Spec\StringType
    {
        if (!\array_key_exists('String', self::$builtInTypes)) {
            self::$builtInTypes['String'] = new \Graphpinator\Type\Spec\StringType();
        }

        return self::$builtInTypes['String'];
    }

    /**
     * Built-in Boolean type.
     */
    public static function Boolean() : \Graphpinator\Type\Spec\BooleanType
    {
        if (!\array_key_exists('Boolean', self::$builtInTypes)) {
            self::$builtInTypes['Boolean'] = new \Graphpinator\Type\Spec\BooleanType();
        }

        return self::$builtInTypes['Boolean'];
    }

    /**
     * Built-in ID type.
     */
    public static function ID() : \Graphpinator\Type\Spec\IdType
    {
        if (!\array_key_exists('ID', self::$builtInTypes)) {
            self::$builtInTypes['ID'] = new \Graphpinator\Type\Spec\IdType();
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

    /**
     * Built-in Specified By directive.
     */
    public static function directiveSpecifiedBy() : \Graphpinator\Directive\Spec\SpecifiedByDirective
    {
        if (!\array_key_exists('specifiedBy', self::$builtInDirectives)) {
            self::$builtInDirectives['specifiedBy'] = new \Graphpinator\Directive\Spec\SpecifiedByDirective();
        }

        return self::$builtInDirectives['specifiedBy'];
    }
}
//@phpcs:enable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
