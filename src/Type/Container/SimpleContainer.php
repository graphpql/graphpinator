<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Container;

/**
 * Simple Container implementation
 */
class SimpleContainer extends Container
{
    protected array $types = [];
    protected array $directives = [];

    /**
     * @param \Graphpinator\Type\Contract\NamedDefinition[] $types
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
    }

    public function getType(string $name) : \Graphpinator\Type\Contract\NamedDefinition
    {
        return $this->types[$name];
    }

    public function getAllTypes() : array
    {
        return $this->types;
    }

    public function getDirective(string $name) : \Graphpinator\Directive\Directive
    {
        return $this->directives[$name];
    }

    public function getAllDirectives() : array
    {
        return $this->directives;
    }
}
