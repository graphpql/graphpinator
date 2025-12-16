<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\DirectiveUsage;

use Graphpinator\Exception\DuplicateNonRepeatableDirective;
use Graphpinator\Graphpinator;
use Graphpinator\Typesystem\Contract\TypeSystemDirective;
use Infinityloop\Utils\ObjectSet;

/**
 * @method DirectiveUsage current() : object
 * @method DirectiveUsage offsetGet($offset) : object
 */
final class DirectiveUsageSet extends ObjectSet
{
    protected const INNER_CLASS = DirectiveUsage::class;

    private array $nonRepeatableDirectives = [];

    #[\Override]
    protected function offsetSetImpl($offset, object $object) : void
    {
        \assert($object instanceof DirectiveUsage);

        if (Graphpinator::$validateSchema && !$object->getDirective()->isRepeatable()) {
            $this->checkForDuplicate($object->getDirective());
        }

        parent::offsetSetImpl($offset, $object);
    }

    private function checkForDuplicate(TypeSystemDirective $directive) : void
    {
        if (!\in_array($directive->getName(), $this->nonRepeatableDirectives, true)) {
            $this->nonRepeatableDirectives[] = $directive->getName();

            return;
        }

        throw new DuplicateNonRepeatableDirective();
    }
}
