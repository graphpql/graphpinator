<?php

declare(strict_types = 1);

namespace Graphpinator\Directive;

trait THasDirectives
{
    protected \Graphpinator\Directive\DirectiveUsageSet $directiveUsages;
    protected string $directiveLocation;

    public function getDirectiveUsages() : \Graphpinator\Directive\DirectiveUsageSet
    {
        return $this->directiveUsages;
    }

    public function addDirective(
        \Graphpinator\Directive\Contract\TypeSystemDefinition $directive,
        array $arguments,
    ) : static
    {
        if (!\in_array($this->directiveLocation, $directive->getLocations(), true)) {
            throw new \Graphpinator\Exception\Normalizer\DirectiveIncorrectLocation();
        }

        $type = match ($this->directiveLocation) {
            TypeSystemDirectiveLocation::OBJECT,
            TypeSystemDirectiveLocation::INTERFACE,
            TypeSystemDirectiveLocation::INPUT_OBJECT => $this,
            TypeSystemDirectiveLocation::FIELD_DEFINITION,
            TypeSystemDirectiveLocation::ARGUMENT_DEFINITION => $this->getType(),
            TypeSystemDirectiveLocation::ENUM_VALUE => null,
        };
        $usage = new DirectiveUsage($directive, $type, $arguments);

        $this->directiveUsages[] = $usage;

        return $this;
    }

    public function printDirectives() : string
    {
        $return = '';

        foreach ($this->directiveUsages as $directiveUsage) {
            $return .= ' ' . $directiveUsage->printSchema();
        }

        return $return;
    }
}
