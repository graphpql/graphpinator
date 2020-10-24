<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

final class RgbaInput extends \Graphpinator\Type\Addon\RgbInput
{
    protected const NAME = 'RgbaInput';
    protected const DESCRIPTION = 'Rgb input - input for the RGB color model with added alpha (transparency).';

    protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
    {
        return parent::getFieldDefinition()->merge(
            new \Graphpinator\Argument\ArgumentSet([
                (new \Graphpinator\Argument\Argument(
                    'alpha',
                    \Graphpinator\Container\Container::Float()->notNull(),
                ))->addConstraint(new \Graphpinator\Constraint\FloatConstraint(0.0, 1.0)),
            ]),
        );
    }
}
