<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

final class PointInput extends \Graphpinator\Type\InputType
{
    protected const NAME = 'PointInput';
    protected const DESCRIPTION = 'Point input - input for the Point.';

    protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
    {
        return new \Graphpinator\Argument\ArgumentSet([
            new \Graphpinator\Argument\Argument(
                'x',
                \Graphpinator\Container\Container::Float()->notNull(),
            ),
            new \Graphpinator\Argument\Argument(
                'y',
                \Graphpinator\Container\Container::Float()->notNull(),
            ),
        ]);
    }
}
