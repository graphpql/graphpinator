<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

final class RgbaInput extends \Graphpinator\Type\Addon\RgbInput
{
    protected const NAME = 'Rgba';
    protected const DESCRIPTION = 'This add on scalar validates rgba object input with keys and its values -
    red (0-255), green (0-255), blue (0-255), alpha (0-1).
    Examples - (object) ["red" => 100, "green" => 50, "blue" => 50, "alpha" => 0.5],
               (object) ["red" => 255, "green" => 255, "blue" => 255, "alpha" => 1.0],
               (object) ["red" => 0, "green" => 0, "blue" => 0, "alpha" => 0.0]';

    protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
    {
        return parent::getFieldDefinition()->merge(
            new \Graphpinator\Argument\ArgumentSet([
                (new \Graphpinator\Argument\Argument(
                    'alpha',
                    \Graphpinator\Type\Container\Container::Float()->notNull(),
                    0.0,
                ))->addConstraint(new \Graphpinator\Constraint\FloatConstraint(0, 1)),
            ]),
        );
    }
}
