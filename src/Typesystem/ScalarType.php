<?php

declare(strict_types = 1);

namespace Graphpinator\Type;

abstract class ScalarType extends \Graphpinator\Type\Contract\LeafType
{
    use Graphpinator\Typesystem\Utils\THasDirectives;

    public function __construct()
    {
        $this->directiveUsages = new \Graphpinator\DirectiveUsage\DirectiveUsageSet();
    }

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
        $this->directiveUsages[] = new \Graphpinator\DirectiveUsage\DirectiveUsage($directive, $arguments);

        return $this;
    }

    public function setSpecifiedBy(string $url) : self
    {
        $this->addDirective(
            \Graphpinator\Container\Container::directiveSpecifiedBy(),
            ['url' => $url],
        );

        return $this;
    }

    public function getSpecifiedByUrl() : ?string
    {
        foreach ($this->getDirectiveUsages() as $directive) {
            if ($directive->getDirective() instanceof \Graphpinator\Directive\Spec\SpecifiedByDirective) {
                return $directive->getArgumentValues()->offsetGet('url')->getValue()->getRawValue();
            }
        }

        return null;
    }
}
