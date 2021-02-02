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

    public function addDirective(\Graphpinator\Directive\DirectiveUsage $directive) : static
    {
        if (!\in_array($this->directiveLocation, $directive->getDirective()->getLocations(), true)) {
            throw new \Graphpinator\Exception\Normalizer\DirectiveIncorrectLocation();
        }

        $this->directives[] = $directive;

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
