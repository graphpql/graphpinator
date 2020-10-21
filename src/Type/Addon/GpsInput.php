<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

final class GpsInput extends \Graphpinator\Type\InputType
{
    protected const NAME = 'GpsInput';
    protected const DESCRIPTION = 'Gps input - input for the GPS.';

    protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
    {
        return new \Graphpinator\Argument\ArgumentSet([
            (new \Graphpinator\Argument\Argument(
                'lat',
                \Graphpinator\Container\Container::Float()->notNull(),
                0.0,
            ))->addConstraint(new \Graphpinator\Constraint\FloatConstraint(-90.0, 90.0)),
            (new \Graphpinator\Argument\Argument(
                'lng',
                \Graphpinator\Container\Container::Float()->notNull(),
                0.0,
            ))->addConstraint(new \Graphpinator\Constraint\FloatConstraint(-180.0, 180.0)),
        ]);
    }
}
