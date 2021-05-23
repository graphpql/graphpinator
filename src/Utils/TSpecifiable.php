<?php

declare(strict_types = 1);

namespace Graphpinator\Utils;

trait TSpecifiable
{
    public function setSpecified(string $by) : self
    {
        $this->addDirective(
            \Graphpinator\Container\Container::directiveSpecifiedBy(),
            ['by' => $by],
        );

        return $this;
    }

    public function isSpecified() : bool
    {
        foreach ($this->directiveUsages as $directive) {
            if ($directive->getDirective() instanceof \Graphpinator\Directive\Spec\SpecifiedByDirective) {
                return true;
            }
        }

        return false;
    }

    public function getSpecified() : ?string
    {
        foreach ($this->directiveUsages as $directive) {
            if ($directive->getDirective() instanceof \Graphpinator\Directive\Spec\SpecifiedByDirective) {
                return $directive->getArgumentValues()->offsetGet('by')->getValue()->getRawValue();
            }
        }

        return null;
    }
}
