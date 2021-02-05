<?php

declare(strict_types = 1);

namespace Graphpinator\Directive;

trait THasDirectives
{
    protected \Graphpinator\Directive\DirectiveUsageSet $directives;
    protected string $directiveLocation;

    public function getDirectives() : \Graphpinator\Directive\DirectiveUsageSet
    {
        return $this->directives;
    }

    public function addDirective(
        \Graphpinator\Directive\Contract\TypeSystemDefinition $directive,
        array $arguments,
    ) : static
    {
        if (!\in_array($this->directiveLocation, $directive->getLocations(), true)) {
            throw new \Graphpinator\Exception\Normalizer\DirectiveIncorrectLocation();
        }

        $usage = new DirectiveUsage($directive, $arguments);
        $type = match ($this->directiveLocation) {
            TypeSystemDirectiveLocation::OBJECT,
            TypeSystemDirectiveLocation::INTERFACE,
            TypeSystemDirectiveLocation::INPUT_OBJECT => $this,
            TypeSystemDirectiveLocation::FIELD_DEFINITION,
            TypeSystemDirectiveLocation::ARGUMENT_DEFINITION => $this->getType(),
            TypeSystemDirectiveLocation::ENUM_VALUE => null,
        };

        if (!$directive->validateType($type, $usage->getArguments())) {
            throw new \Graphpinator\Exception\Constraint\InvalidConstraintType();
        }

        $this->directives[] = $usage;

        return $this;
    }

    public function printDirectives() : string
    {
        $return = '';

        foreach ($this->directives as $directive) {
            $return .= ' ' . $directive->print();
        }

        return $return;
    }
}
