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
                \Graphpinator\Argument\Argument::create(
                    'alpha',
                    \Graphpinator\Container\Container::Float()->notNull(),
                )->addDirective(
                    \Graphpinator\Container\Container::directiveFloatConstraint(),
                    ['min' => 0.0, 'max' => 1.0],
                ),
            ]),
        );
    }
}
