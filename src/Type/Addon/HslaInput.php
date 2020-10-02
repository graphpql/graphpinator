<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

final class HslaInput extends \Graphpinator\Type\Addon\HslInput
{
    protected const NAME = 'HslaInput';
    protected const DESCRIPTION = 'Hsla input - input for the HSL color model with added alpha (transparency).';

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
