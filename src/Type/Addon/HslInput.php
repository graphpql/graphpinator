<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

class HslInput extends \Graphpinator\Type\InputType
{
    protected const NAME = 'Hsl';
    protected const DESCRIPTION = 'This add on scalar validates hsl object input with keys and its values -
    hue (0-360), saturation (0-100), lightness (0-100).
    Examples - (object) ["hue" => 180, "saturation\" => 50, "lightness" => 50],
               (object) ["hue" => 360, "saturation\" => 100, "lightness" => 100],
               (object) ["hue" => 0, "saturation" => 0, "lightness" => 0]';

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
