<?php

declare(strict_types = 1);

namespace Graphpinator\Constraint;

final class ListConstraintInput extends \Graphpinator\Type\InputType
{
    protected const NAME = 'ListConstraintInput';

    protected function getFieldDefinition(): \Graphpinator\Argument\ArgumentSet
    {
        return new \Graphpinator\Argument\ArgumentSet([
            new \Graphpinator\Argument\Argument('minItems', \Graphpinator\Type\Container\Container::Int()),
            new \Graphpinator\Argument\Argument('maxItems', \Graphpinator\Type\Container\Container::Int()),
            new \Graphpinator\Argument\Argument('unique', \Graphpinator\Type\Container\Container::Boolean(), false),
            new \Graphpinator\Argument\Argument('innerList', $this),
        ]);
    }
}
