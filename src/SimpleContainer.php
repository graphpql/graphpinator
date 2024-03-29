<?php

declare(strict_types = 1);

namespace Graphpinator;

/**
 * Simple Container implementation
 */
class SimpleContainer extends \Graphpinator\Typesystem\Container
{
    protected array $types = [];
    protected array $directives = [];
    protected array $combinedTypes = [];
    protected array $combinedDirectives = [];

    /**
     * @phpcs:ignore SlevomatCodingStandard.TypeHints.DisallowArrayTypeHintSyntax.DisallowedArrayTypeHintSyntax
     * @param \Graphpinator\Typesystem\Contract\NamedType[] $types
     * @phpcs:ignore SlevomatCodingStandard.TypeHints.DisallowArrayTypeHintSyntax.DisallowedArrayTypeHintSyntax
     * @param \Graphpinator\Typesystem\Directive[] $directives
     */
    public function __construct(array $types, array $directives)
    {
        self::$builtInTypes = [
            'ID' => self::ID(),
            'Int' => self::Int(),
            'Float' => self::Float(),
            'String' => self::String(),
            'Boolean' => self::Boolean(),
            '__Schema' => new \Graphpinator\Introspection\Schema($this),
            '__Type' => new \Graphpinator\Introspection\Type($this),
            '__TypeKind' => new \Graphpinator\Introspection\TypeKind(),
            '__Field' => new \Graphpinator\Introspection\Field($this),
            '__EnumValue' => new \Graphpinator\Introspection\EnumValue(),
            '__InputValue' => new \Graphpinator\Introspection\InputValue($this),
            '__Directive' => new \Graphpinator\Introspection\Directive($this),
            '__DirectiveLocation' => new \Graphpinator\Introspection\DirectiveLocation(),
        ];
        self::$builtInDirectives = [
            'skip' => self::directiveSkip(),
            'include' => self::directiveInclude(),
            'deprecated' => self::directiveDeprecated(),
            'specifiedBy' => self::directiveSpecifiedBy(),
            'oneOf' => self::directiveOneOf(),
        ];

        foreach ($types as $type) {
            $this->types[$type->getName()] = $type;
        }

        foreach ($directives as $directive) {
            $this->directives[$directive->getName()] = $directive;
        }

        $this->combinedTypes = \array_merge($this->types, self::$builtInTypes);
        $this->combinedDirectives = \array_merge($this->directives, self::$builtInDirectives);
    }

    public function getType(string $name) : ?\Graphpinator\Typesystem\Contract\NamedType
    {
        return $this->combinedTypes[$name]
            ?? null;
    }

    public function getTypes(bool $includeBuiltIn = false) : array
    {
        return $includeBuiltIn
            ? $this->combinedTypes
            : $this->types;
    }

    public function getDirective(string $name) : ?\Graphpinator\Typesystem\Directive
    {
        return $this->combinedDirectives[$name]
            ?? null;
    }

    public function getDirectives(bool $includeBuiltIn = false) : array
    {
        return $includeBuiltIn
            ? $this->combinedDirectives
            : $this->directives;
    }
}
