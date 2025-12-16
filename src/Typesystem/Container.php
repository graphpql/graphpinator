<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem;

use Graphpinator\Introspection\Directive as IntrospectionDirective;
use Graphpinator\Introspection\DirectiveLocation;
use Graphpinator\Introspection\EnumValue;
use Graphpinator\Introspection\Field;
use Graphpinator\Introspection\InputValue;
use Graphpinator\Introspection\Schema;
use Graphpinator\Introspection\Type;
use Graphpinator\Introspection\TypeKind;
use Graphpinator\Typesystem\Contract\NamedType;
use Graphpinator\Typesystem\Spec\BooleanType;
use Graphpinator\Typesystem\Spec\DeprecatedDirective;
use Graphpinator\Typesystem\Spec\FloatType;
use Graphpinator\Typesystem\Spec\IdType;
use Graphpinator\Typesystem\Spec\IncludeDirective;
use Graphpinator\Typesystem\Spec\IntType;
use Graphpinator\Typesystem\Spec\OneOfDirective;
use Graphpinator\Typesystem\Spec\SkipDirective;
use Graphpinator\Typesystem\Spec\SpecifiedByDirective;
use Graphpinator\Typesystem\Spec\StringType;

/**
 * Class Container, which is responsible for fetching instances of type classes.
 */
abstract class Container
{
    /**
     * @var array{
     *     ID?: IdType,
     *     Int?: IntType,
     *     Float?: FloatType,
     *     String?: StringType,
     *     Boolean?: BooleanType,
     *     __Schema?: Schema,
     *     __Type?: Type,
     *     __TypeKind?: TypeKind,
     *     __Field?: Field,
     *     __EnumValue?: EnumValue,
     *     __InputValue?: InputValue,
     *     __Directive?: IntrospectionDirective,
     *     __DirectiveLocation?: DirectiveLocation,
     * }
     */
    protected static array $builtInTypes = [];
    /**
     * @var array{
     *     skip?: SkipDirective,
     *     include?: IncludeDirective,
     *     deprecated?: DeprecatedDirective,
     *     specifiedBy?: SpecifiedByDirective,
     *     oneOf?: OneOfDirective,
     * }
     */
    protected static array $builtInDirectives = [];

    /**
     * Core function to find a type by its name.
     * @param string $name
     */
    abstract public function getType(string $name) : ?NamedType;

    /**
     * Function to return all user-defined types.
     * @param bool $includeBuiltIn
     */
    abstract public function getTypes(bool $includeBuiltIn = false) : array;

    /**
     * Core function to find a directive by its name.
     * @param string $name
     */
    abstract public function getDirective(string $name) : ?Directive;

    /**
     * Function to return all user-defined directives.
     * @param bool $includeBuiltIn
     */
    abstract public function getDirectives(bool $includeBuiltIn = false) : array;

    /**
     * Built-in Int type.
     */
    public static function Int() : IntType // @phpcs:ignore
    {
        if (!\array_key_exists('Int', self::$builtInTypes)) {
            self::$builtInTypes['Int'] = new IntType();
        }

        return self::$builtInTypes['Int'];
    }

    /**
     * Built-in Float type.
     */
    public static function Float() : FloatType // @phpcs:ignore
    {
        if (!\array_key_exists('Float', self::$builtInTypes)) {
            self::$builtInTypes['Float'] = new FloatType();
        }

        return self::$builtInTypes['Float'];
    }

    /**
     * Built-in String type.
     */
    public static function String() : StringType // @phpcs:ignore
    {
        if (!\array_key_exists('String', self::$builtInTypes)) {
            self::$builtInTypes['String'] = new StringType();
        }

        return self::$builtInTypes['String'];
    }

    /**
     * Built-in Boolean type.
     */
    public static function Boolean() : BooleanType // @phpcs:ignore
    {
        if (!\array_key_exists('Boolean', self::$builtInTypes)) {
            self::$builtInTypes['Boolean'] = new BooleanType();
        }

        return self::$builtInTypes['Boolean'];
    }

    /**
     * Built-in ID type.
     */
    public static function ID() : IdType // @phpcs:ignore
    {
        if (!\array_key_exists('ID', self::$builtInTypes)) {
            self::$builtInTypes['ID'] = new IdType();
        }

        return self::$builtInTypes['ID'];
    }

    /**
     * Built-in Skip directive.
     */
    public static function directiveSkip() : SkipDirective
    {
        if (!\array_key_exists('skip', self::$builtInDirectives)) {
            self::$builtInDirectives['skip'] = new SkipDirective();
        }

        return self::$builtInDirectives['skip'];
    }

    /**
     * Built-in Include directive.
     */
    public static function directiveInclude() : IncludeDirective
    {
        if (!\array_key_exists('include', self::$builtInDirectives)) {
            self::$builtInDirectives['include'] = new IncludeDirective();
        }

        return self::$builtInDirectives['include'];
    }

    /**
     * Built-in Deprecated directive.
     */
    public static function directiveDeprecated() : DeprecatedDirective
    {
        if (!\array_key_exists('deprecated', self::$builtInDirectives)) {
            self::$builtInDirectives['deprecated'] = new DeprecatedDirective();
        }

        return self::$builtInDirectives['deprecated'];
    }

    /**
     * Built-in SpecifiedBy directive.
     */
    public static function directiveSpecifiedBy() : SpecifiedByDirective
    {
        if (!\array_key_exists('specifiedBy', self::$builtInDirectives)) {
            self::$builtInDirectives['specifiedBy'] = new SpecifiedByDirective();
        }

        return self::$builtInDirectives['specifiedBy'];
    }

    /**
     * Built-in SpecifiedBy directive.
     */
    public static function directiveOneOf() : OneOfDirective
    {
        if (!\array_key_exists('oneOf', self::$builtInDirectives)) {
            self::$builtInDirectives['oneOf'] = new OneOfDirective();
        }

        return self::$builtInDirectives['oneOf'];
    }
}
