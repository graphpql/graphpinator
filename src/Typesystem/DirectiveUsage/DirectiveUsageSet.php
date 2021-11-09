<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\DirectiveUsage;

use \Graphpinator\Exception\DuplicateNonRepeatableDirective;
use \Graphpinator\Typesystem\Contract\TypeSystemDirective;

/**
 * @method \Graphpinator\Typesystem\DirectiveUsage\DirectiveUsage current() : object
 * @method \Graphpinator\Typesystem\DirectiveUsage\DirectiveUsage offsetGet($offset) : object
 */
final class DirectiveUsageSet extends \Infinityloop\Utils\ObjectSet
{
    protected const INNER_CLASS = DirectiveUsage::class;
    private array $nonRepeatableDirectives = [];

    protected function offsetSetImpl($offset, object $object) : void
    {
        \assert($object instanceof DirectiveUsage);

        if (\Graphpinator\Graphpinator::$validateSchema && !$object->getDirective()->isRepeatable()) {
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
