<?php

declare(strict_types = 1);

namespace Graphpinator\Directive;

/**
 * Trait TDeprecatable which manages deprecated info for classes which support it.
 */
trait TDeprecatable
{
    public function setDeprecated(?string $reason = null) : self
    {
        $this->directives[] = new \Graphpinator\Directive\DirectiveUsage(
            \Graphpinator\Container\Container::directiveDeprecated(),
            ['reason' => $reason]
        );

        return $this;
    }

    public function isDeprecated() : bool
    {
        foreach ($this->directives as $directive) {
            if ($directive->getDirective() instanceof \Graphpinator\Directive\Spec\DeprecatedDirective) {
                return true;
            }
        }

        return false;
    }

    public function getDeprecationReason() : ?string
    {
        foreach ($this->directives as $directive) {
            if ($directive->getDirective() instanceof \Graphpinator\Directive\Spec\DeprecatedDirective) {
                return $directive->getArguments()->offsetGet('reason')->getValue()->getRawValue();
            }
        }

        return null;
    }
}
