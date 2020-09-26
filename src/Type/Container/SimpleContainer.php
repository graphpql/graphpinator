<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Container;

/**
 * Simple Container implementation
 */
class SimpleContainer extends \Graphpinator\Type\Container\Container
{
    protected array $types = [];
    protected array $directives = [];
    protected array $builtInTypes = [];
    protected array $builtInDirectives = [];
    protected array $combinedTypes = [];
    protected array $combinedDirectives = [];

    /**
     * @phpcs:ignore SlevomatCodingStandard.TypeHints.DisallowArrayTypeHintSyntax.DisallowedArrayTypeHintSyntax
     * @param \Graphpinator\Type\Contract\NamedDefinition[] $types
     * @phpcs:ignore SlevomatCodingStandard.TypeHints.DisallowArrayTypeHintSyntax.DisallowedArrayTypeHintSyntax
     * @param \Graphpinator\Directive\Directive[] $directives
     */
    public function __construct(array $types, array $directives)
    {
        foreach ($types as $type) {
            $this->types[$type->getName()] = $type;
        }

        foreach ($directives as $directive) {
            $this->directives[$directive->getName()] = $directive;
        }

        $this->builtInTypes = [
            'ID' => self::ID(),
            'Int' => self::Int(),
            'Float' => self::Float(),
            'String' => self::String(),
            'Boolean' => self::Boolean(),
            '__Schema' => $this->introspectionSchema(),
            '__Type' => $this->introspectionType(),
            '__TypeKind' => $this->introspectionTypeKind(),
            '__Field' => $this->introspectionField(),
            '__EnumValue' => $this->introspectionEnumValue(),
            '__InputValue' => $this->introspectionInputValue(),
            '__Directive' => $this->introspectionDirective(),
            '__DirectiveLocation' => $this->introspectionDirectiveLocation(),
        ];

        $this->builtInDirectives = [
            'skip' => self::directiveSkip(),
            'include' => self::directiveInclude(),
            'deprecated' => self::directiveDeprecated(),
        ];

        $this->types['ListConstraintInput'] = self::listConstraintInput();
        $this->directives['intConstraint'] = self::directiveIntConstraint();
        $this->directives['floatConstraint'] = self::directiveFloatConstraint();
        $this->directives['stringConstraint'] = self::directiveStringConstraint();
        $this->directives['listConstraint'] = self::directiveListConstraint();
        $this->directives['inputConstraint'] = self::directiveInputConstraint();

        $this->combinedTypes = \array_merge($this->types, $this->builtInTypes);
        $this->combinedDirectives = \array_merge($this->directives, $this->builtInDirectives);
    }

    public function getType(string $name) : \Graphpinator\Type\Contract\NamedDefinition
    {
        return $this->combinedTypes[$name];
    }

    public function getTypes(bool $includeBuiltIn = false) : array
    {
        return $includeBuiltIn
            ? $this->combinedTypes
            : $this->types;
    }

    public function getDirective(string $name) : \Graphpinator\Directive\Directive
    {
        return $this->combinedDirectives[$name];
    }

    public function getDirectives(bool $includeBuiltIn = false) : array
    {
        return $includeBuiltIn
            ? $this->combinedDirectives
            : $this->directives;
    }
}
