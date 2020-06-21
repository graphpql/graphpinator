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

    /**
     * @param array<\Graphpinator\Type\Contract\NamedDefinition> $types
     * @param array<\Graphpinator\Directive\Directive> $directives
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
            '__TypeKind' => $this->introspectionField(),
            '__Field' => $this->introspectionField(),
            '__EnumValue' => $this->introspectionEnumValue(),
            '__InputValue' => $this->introspectionInputValue(),
            '__Directive' => $this->introspectionDirective(),
            '__DirectiveLocation' => $this->introspectionDirectiveLocation(),
        ];

        $this->builtInDirectives = [
            'skip' => self::directiveSkip(),
            'include' => self::directiveInclude(),
        ];
    }

    public function getType(string $name) : \Graphpinator\Type\Contract\NamedDefinition
    {
        return $this->builtInTypes[$name]
            ?? $this->types[$name];
    }

    public function getTypes() : array
    {
        return $this->types;
    }

    public function getDirective(string $name) : \Graphpinator\Directive\Directive
    {
        return $this->builtInDirectives[$name]
            ?? $this->directives[$name];
    }

    public function getDirectives() : array
    {
        return $this->directives;
    }
}
