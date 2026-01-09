<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem;

use Graphpinator\Typesystem\Argument\ArgumentSet;
use Graphpinator\Typesystem\Contract\NamedType;
use Graphpinator\Typesystem\Contract\NamedTypeVisitor;
use Graphpinator\Typesystem\DirectiveUsage\DirectiveUsage;
use Graphpinator\Typesystem\DirectiveUsage\DirectiveUsageSet;
use Graphpinator\Typesystem\Exception\ArgumentDefaultValueCycleDetected;
use Graphpinator\Typesystem\Location\InputObjectLocation;
use Graphpinator\Typesystem\Spec\OneOfDirective;
use Graphpinator\Typesystem\Utils\THasDirectives;

abstract class InputType extends NamedType
{
    use THasDirectives;

    protected const DATA_CLASS = \stdClass::class;

    protected ?ArgumentSet $arguments = null;
    private bool $isLoadingArguments = false;

    public function __construct()
    {
        $this->directiveUsages = new DirectiveUsageSet();
    }

    final public function getArguments() : ArgumentSet
    {
        if (!$this->arguments instanceof ArgumentSet) {
            if ($this->isLoadingArguments) {
                throw new ArgumentDefaultValueCycleDetected($this->getName());
            }

            $this->isLoadingArguments = true;
            $this->arguments = $this->getFieldDefinition();
            $this->afterGetFieldDefinition();
        }

        return $this->arguments;
    }

    #[\Override]
    final public function accept(NamedTypeVisitor $visitor) : mixed
    {
        return $visitor->visitInput($this);
    }

    final public function getDataClass() : string
    {
        return static::DATA_CLASS;
    }

    /**
     * @param InputObjectLocation $directive
     * @phpcs:ignore
     * @param array<string, mixed> $arguments
     */
    final public function addDirective(InputObjectLocation $directive, array $arguments = []) : static
    {
        $this->directiveUsages[] = new DirectiveUsage($directive, $arguments);

        return $this;
    }

    public function isOneOf() : bool
    {
        foreach ($this->getDirectiveUsages() as $directive) {
            if ($directive->getDirective() instanceof OneOfDirective) {
                return true;
            }
        }

        return false;
    }

    abstract protected function getFieldDefinition() : ArgumentSet;

    /**
     * This function serves to prevent infinite cycles.
     *
     * It doesn't have to be used at all, unless input have arguments self referencing fields and wish to put default value for them.
     */
    protected function afterGetFieldDefinition() : void
    {
    }
}
