<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem;

use Graphpinator\Typesystem\Contract\LeafType;
use Graphpinator\Typesystem\Contract\NamedType;
use Graphpinator\Typesystem\Contract\NamedTypeVisitor;
use Graphpinator\Typesystem\DirectiveUsage\DirectiveUsage;
use Graphpinator\Typesystem\DirectiveUsage\DirectiveUsageSet;
use Graphpinator\Typesystem\Location\ScalarLocation;
use Graphpinator\Typesystem\Spec\SpecifiedByDirective;
use Graphpinator\Typesystem\Utils\THasDirectives;

/**
 * @phpcs:ignore
 * @template T of mixed
 */
abstract class ScalarType extends NamedType implements LeafType
{
    use THasDirectives;

    public function __construct()
    {
        $this->directiveUsages = new DirectiveUsageSet();
    }

    #[\Override]
    final public function accept(NamedTypeVisitor $visitor) : mixed
    {
        return $visitor->visitScalar($this);
    }

    /**
     * @phpcs:ignore
     * @param mixed $rawValue
     * @return ?T
     */
    abstract public function validateAndCoerceInput(mixed $rawValue) : mixed;

    /**
     * @param T $rawValue
     */
    abstract public function coerceOutput(mixed $rawValue) : string|int|float|bool;

    /**
     * @param ScalarLocation $directive
     * @phpcs:ignore
     * @param array<string, mixed> $arguments
     */
    final public function addDirective(ScalarLocation $directive, array $arguments = []) : static
    {
        $this->directiveUsages[] = new DirectiveUsage($directive, $arguments);

        return $this;
    }

    public function setSpecifiedBy(string $url) : self
    {
        $this->addDirective(
            Container::directiveSpecifiedBy(),
            ['url' => $url],
        );

        return $this;
    }

    public function getSpecifiedByUrl() : ?string
    {
        foreach ($this->getDirectiveUsages() as $directive) {
            if ($directive->getDirective() instanceof SpecifiedByDirective) {
                return $directive->getArgumentValues()->offsetGet('url')->getValue()->getRawValue();
            }
        }

        return null;
    }
}
