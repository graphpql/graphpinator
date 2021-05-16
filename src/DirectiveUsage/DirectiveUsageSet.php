<?php

declare(strict_types = 1);

namespace Graphpinator\DirectiveUsage;

/**
 * @method \Graphpinator\DirectiveUsage\DirectiveUsage current() : object
 * @method \Graphpinator\DirectiveUsage\DirectiveUsage offsetGet($offset) : object
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

        foreach ($biggerSet as $usage) {
            $directive = $usage->getDirective();
            \assert($directive instanceof \Graphpinator\Directive\Contract\FieldDefinitionLocation
                || $directive instanceof \Graphpinator\Directive\Contract\ArgumentDefinitionLocation);

            if ($smallerSet->offsetExists($childIndex) && $directive instanceof ($smallerSet->offsetGet($childIndex)->getDirective())) {
                $directive->validateVariance(
                    $usage->getArgumentValues(),
                    $smallerSet->offsetGet($childIndex)->getArgumentValues(),
                );
                ++$childIndex;

                continue;
            }

            $directive->validateVariance(
                $usage->getArgumentValues(),
                null,
            );
        }
    }
}
