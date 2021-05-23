<?php

declare(strict_types = 1);

namespace Graphpinator\Type;

abstract class ScalarType extends \Graphpinator\Type\Contract\LeafDefinition
{
    use \Graphpinator\Utils\THasDirectives;

    final public function accept(\Graphpinator\Typesystem\NamedTypeVisitor $visitor) : mixed
    {
        return $visitor->visitScalar($this);
    }

    public function coerceValue(mixed $rawValue) : mixed
    {
        return $rawValue;
    }

    final public function addDirective(
        \Graphpinator\Directive\Contract\ScalarLocation $directive,
        array $arguments = [],
    ) : static
    {
        $usage = new \Graphpinator\DirectiveUsage\DirectiveUsage($directive, $arguments);
        $this->directiveUsages[] = $usage;
        
        return $this;
    }

    public function setSpecified(string $url) : self
    {
        $this->addDirective(
            \Graphpinator\Container\Container::directiveSpecifiedBy(),
            ['url' => $url],
        );

        return $this;
    }

}
