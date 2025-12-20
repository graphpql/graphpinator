<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Selection;

use Graphpinator\Normalizer\VariableValueSet;
use Graphpinator\Normalizer\Visitor\ApplyVariablesVisitor;
use Infinityloop\Utils\ObjectSet;

/**
 * @method Selection current() : object
 * @method Selection offsetGet($offset) : object
 */
final class SelectionSet extends ObjectSet
{
    protected const INNER_CLASS = Selection::class;

    public function applyVariables(VariableValueSet $variables) : void
    {
        $visitor = new ApplyVariablesVisitor($variables);

        foreach ($this as $field) {
            $field->accept($visitor);
        }
    }
}
