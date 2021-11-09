<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Utils;

use \Graphpinator\Typesystem\Spec\DeprecatedDirective;

/**
 * Trait TDeprecatable which manages deprecated info for classes which support it.
 */
trait TDeprecatable
{
    public function setDeprecated(?string $reason = null) : self
    {
        $this->addDirective(
            \Graphpinator\Typesystem\Container::directiveDeprecated(),
            ['reason' => $reason],
        );

        return $this;
    }

    public function isDeprecated() : bool
    {
        foreach ($this->directiveUsages as $directive) {
            if ($directive->getDirective() instanceof DeprecatedDirective) {
                return true;
            }
        }

        return false;
    }

    public function getDeprecationReason() : ?string
    {
        foreach ($this->directiveUsages as $directive) {
            if ($directive->getDirective() instanceof DeprecatedDirective) {
                return $directive->getArgumentValues()->offsetGet('reason')->getValue()->getRawValue();
            }
        }

        return null;
    }
}
