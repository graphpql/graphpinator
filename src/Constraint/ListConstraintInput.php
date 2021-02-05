<?php

declare(strict_types = 1);

namespace Graphpinator\Constraint;

final class ListConstraintInput extends \Graphpinator\Type\InputType
{
    protected const NAME = 'ListConstraintInput';

    protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
    {
        return new \Graphpinator\Argument\ArgumentSet([
            \Graphpinator\Argument\Argument::create('minItems', \Graphpinator\Container\Container::Int())
                ->addDirective(
                    \Graphpinator\Container\Container::directiveIntConstraint(),
                    ['min' => 0],
                ),
            \Graphpinator\Argument\Argument::create('maxItems', \Graphpinator\Container\Container::Int())
                ->addDirective(
                    \Graphpinator\Container\Container::directiveIntConstraint(),
                    ['min' => 0],
                ),
            \Graphpinator\Argument\Argument::create('unique', \Graphpinator\Container\Container::Boolean())->setDefaultValue(false),
            \Graphpinator\Argument\Argument::create('innerList', $this),
        ]);
    }
}
