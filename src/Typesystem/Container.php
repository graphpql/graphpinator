<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem;

/**
 * Class Container which is responsible for fetching instances of type classes.
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
    abstract public function getDirective(string $name) : ?\Graphpinator\Typesystem\Directive;

    /**
     * Function to return all user-defined directives.
     * @param bool $includeBuiltIn
     */
    abstract public function getDirectives(bool $includeBuiltIn = false) : array;

    /**
     * Built-in Int type.
     */
    public static function Int() : \Graphpinator\Typesystem\Spec\IntType
    {
        if (!\array_key_exists('Int', self::$builtInTypes)) {
            self::$builtInTypes['Int'] = new \Graphpinator\Typesystem\Spec\IntType();
        }

        return self::$builtInTypes['Int'];
    }

    /**
     * Built-in Float type.
     */
    public static function Float() : \Graphpinator\Typesystem\Spec\FloatType
    {
        if (!\array_key_exists('Float', self::$builtInTypes)) {
            self::$builtInTypes['Float'] = new \Graphpinator\Typesystem\Spec\FloatType();
        }

        return self::$builtInTypes['Float'];
    }

    /**
     * Built-in String type.
     */
    public static function String() : \Graphpinator\Typesystem\Spec\StringType
    {
        if (!\array_key_exists('String', self::$builtInTypes)) {
            self::$builtInTypes['String'] = new \Graphpinator\Typesystem\Spec\StringType();
        }

        return self::$builtInTypes['String'];
    }

    /**
     * Built-in Boolean type.
     */
    public static function Boolean() : \Graphpinator\Typesystem\Spec\BooleanType
    {
        if (!\array_key_exists('Boolean', self::$builtInTypes)) {
            self::$builtInTypes['Boolean'] = new \Graphpinator\Typesystem\Spec\BooleanType();
        }

        return self::$builtInTypes['Boolean'];
    }

    /**
     * Built-in ID type.
     */
    public static function ID() : \Graphpinator\Typesystem\Spec\IdType
    {
        if (!\array_key_exists('ID', self::$builtInTypes)) {
            self::$builtInTypes['ID'] = new \Graphpinator\Typesystem\Spec\IdType();
        }

        return self::$builtInTypes['ID'];
    }

    /**
     * Built-in Skip directive.
     */
    public static function directiveSkip() : \Graphpinator\Typesystem\Spec\SkipDirective
    {
        if (!\array_key_exists('skip', self::$builtInDirectives)) {
            self::$builtInDirectives['skip'] = new \Graphpinator\Typesystem\Spec\SkipDirective();
        }

        return self::$builtInDirectives['skip'];
    }

    /**
     * Built-in Include directive.
     */
    public static function directiveInclude() : \Graphpinator\Typesystem\Spec\IncludeDirective
    {
        if (!\array_key_exists('include', self::$builtInDirectives)) {
            self::$builtInDirectives['include'] = new \Graphpinator\Typesystem\Spec\IncludeDirective();
        }

        return self::$builtInDirectives['include'];
    }

    /**
     * Built-in Deprecated directive.
     */
    public static function directiveDeprecated() : \Graphpinator\Typesystem\Spec\DeprecatedDirective
    {
        if (!\array_key_exists('deprecated', self::$builtInDirectives)) {
            self::$builtInDirectives['deprecated'] = new \Graphpinator\Typesystem\Spec\DeprecatedDirective();
        }

        return self::$builtInDirectives['deprecated'];
    }

    /**
     * Built-in SpecifiedBy directive.
     */
    public static function directiveSpecifiedBy() : \Graphpinator\Typesystem\Spec\SpecifiedByDirective
    {
        if (!\array_key_exists('specifiedBy', self::$builtInDirectives)) {
            self::$builtInDirectives['specifiedBy'] = new \Graphpinator\Typesystem\Spec\SpecifiedByDirective();
        }

        return self::$builtInDirectives['specifiedBy'];
    }

    /**
     * Built-in SpecifiedBy directive.
     */
    public static function directiveOneOf() : \Graphpinator\Typesystem\Spec\OneOfDirective
    {
        if (!\array_key_exists('oneOf', self::$builtInDirectives)) {
            self::$builtInDirectives['oneOf'] = new \Graphpinator\Typesystem\Spec\OneOfDirective();
        }

        return self::$builtInDirectives['oneOf'];
    }
}
