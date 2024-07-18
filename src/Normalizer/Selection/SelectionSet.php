<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Selection;

use Graphpinator\Normalizer\VariableValueSet;
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
        foreach ($this as $field) {
            $field->applyVariables($variables);
        }
    }
}
