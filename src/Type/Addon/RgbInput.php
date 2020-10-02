<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

class RgbInput extends \Graphpinator\Type\InputType
{
    protected const NAME = 'Rgb';
    protected const DESCRIPTION = 'This add on scalar validates rgb object input with keys and its values -
    red (0-255), green (0-255), blue (0-255).
    Examples - ["red" => 100, "green" => 50, "blue" => 50, "alpha" => 0.5],
               ["red" => 255, "green" => 255, "blue" => 255, "alpha" => 1.0],
               ["red" => 0, "green" => 0, "blue" => 0, "alpha" => 0.0]';

    protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
    {
        return new \Graphpinator\Argument\ArgumentSet([
            (new \Graphpinator\Argument\Argument(
                'red',
                \Graphpinator\Type\Container\Container::Int()->notNull(),
                0,
            ))->addConstraint(new \Graphpinator\Constraint\IntConstraint(0, 255)),
            (new \Graphpinator\Argument\Argument(
                'green',
                \Graphpinator\Type\Container\Container::Int()->notNull(),
                0,
            ))->addConstraint(new \Graphpinator\Constraint\IntConstraint(0, 255)),
            (new \Graphpinator\Argument\Argument(
                'blue',
                \Graphpinator\Type\Container\Container::Int()->notNull(),
                0,
            ))->addConstraint(new \Graphpinator\Constraint\IntConstraint(0, 255)),
        ]);
    }
}
