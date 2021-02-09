<?php

declare(strict_types = 1);

namespace Graphpinator\Directive;

/**
 * @method \Graphpinator\Directive\DirectiveUsage current() : object
 * @method \Graphpinator\Directive\DirectiveUsage offsetGet($offset) : object
 */
final class DirectiveUsageSet extends \Infinityloop\Utils\ObjectSet
{
    protected const INNER_CLASS = DirectiveUsage::class;

    public function validateInvariance(self $child) : void
    {
        foreach ($this as $index => $usage) {
            if ($child->offsetExists($index) &&
                $usage->getDirective() instanceof ($child->offsetGet($index)->getDirective()) &&
                $usage->getArgumentValues()->isSame($child->offsetGet($index)->getArgumentValues())) {
                continue;
            }

            throw new \Graphpinator\Exception\Type\InterfaceDirectivesNotPreserved();
        }
    }

    public function validateCovariance(self $child) : void
    {
        self::compareVariance($this, $child);
    }

    public function validateContravariance(self $child) : void
    {
        self::compareVariance($child, $this);
    }

    private static function compareVariance(self $biggerSet, self $smallerSet) : void
    {
        $childIndex = 0;

        foreach ($biggerSet as $index => $usage) {
            if ($smallerSet->offsetExists($childIndex) && $usage->getDirective() instanceof ($smallerSet->offsetGet($childIndex)->getDirective())) {
                $usage->getDirective()->validateVariance(
                    $usage->getArgumentValues(),
                    $smallerSet->offsetGet($childIndex)->getArgumentValues(),
                );
                ++$childIndex;

                continue;
            }

            $usage->getDirective()->validateVariance(
                $usage->getArgumentValues(),
                null,
            );
        }
    }
}
