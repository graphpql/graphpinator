<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Directive;

use Graphpinator\Normalizer\VariableValueSet;
use Infinityloop\Utils\ObjectSet;

/**
 * @method Directive current() : object
 * @method Directive offsetGet($offset) : object
 */
final class DirectiveSet extends ObjectSet
{
    protected const INNER_CLASS = Directive::class;

    public function applyVariables(VariableValueSet $variables) : void
    {
        foreach ($this as $directive) {
            $directive->applyVariables($variables);
        }
    }

    public function isSame(self $compare) : bool
    {
        if ($compare->count() !== $this->count()) {
            return false;
        }

        foreach ($compare as $index => $compareItem) {
            $thisItem = $this->offsetGet($index);

            if ($thisItem->getDirective()->getName() === $compareItem->getDirective()->getName() &&
                $thisItem->getArguments()->isSame($compareItem->getArguments())) {
                continue;
            }

            return false;
        }

        return true;
    }
}
