<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\DirectiveUsage;

/**
 * @method DirectiveUsage current() : object
 * @method DirectiveUsage offsetGet($offset) : object
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

    private function checkForDuplicate(\Graphpinator\Typesystem\Contract\TypeSystemDirective $directive) : void
    {
        if (!\in_array($directive->getName(), $this->nonRepeatableDirectives, true)) {
            $this->nonRepeatableDirectives[] = $directive->getName();

            return;
        }

        throw new \Graphpinator\Exception\DuplicateNonRepeatableDirective();
    }
}
