<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

class HslInput extends \Graphpinator\Type\InputType
{
    protected const NAME = 'HslInput';
    protected const DESCRIPTION = 'Hsl input - input for the HSL color model.';

    protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
    {
        return new \Graphpinator\Argument\ArgumentSet([
            (new \Graphpinator\Argument\Argument(
                'hue',
                \Graphpinator\Type\Container\Container::Int()->notNull(),
                0,
            ))->addConstraint(new \Graphpinator\Constraint\IntConstraint(0, 360)),
            (new \Graphpinator\Argument\Argument(
                'saturation',
                \Graphpinator\Type\Container\Container::Int()->notNull(),
                0,
            ))->addConstraint(new \Graphpinator\Constraint\IntConstraint(0, 100)),
            (new \Graphpinator\Argument\Argument(
                'lightness',
                \Graphpinator\Type\Container\Container::Int()->notNull(),
                0,
            ))->addConstraint(new \Graphpinator\Constraint\IntConstraint(0, 100)),
        ]);
    }
}
