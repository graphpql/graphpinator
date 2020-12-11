<?php

declare(strict_types = 1);

namespace Graphpinator\Constraint;

final class ListConstraintInput extends \Graphpinator\Type\InputType
{
    protected const NAME = 'ListConstraintInput';

    protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
    {
        return new \Graphpinator\Argument\ArgumentSet([
            new \Graphpinator\Argument\Argument('minItems', \Graphpinator\Container\Container::Int()),
            new \Graphpinator\Argument\Argument('maxItems', \Graphpinator\Container\Container::Int()),
            \Graphpinator\Argument\Argument::create('unique', \Graphpinator\Container\Container::Boolean())->setDefaultValue(false),
            new \Graphpinator\Argument\Argument('innerList', $this),
        ]);
    }
}
