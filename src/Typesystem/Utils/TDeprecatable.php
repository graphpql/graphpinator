<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Utils;

use Graphpinator\Typesystem\Container;
use Graphpinator\Typesystem\Spec\DeprecatedDirective;

/**
 * Trait TDeprecatable which manages deprecated info for classes which support it.
 */
trait TDeprecatable
{
    public function setDeprecated(?string $reason = null) : self
    {
        $this->addDirective(
            Container::directiveDeprecated(),
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
                $value = $directive->getArgumentValues()->offsetGet('reason')->getValue()->getRawValue();

                return \is_string($value)
                    ? $value
                    : null;
            }
        }

        return null;
    }
}
