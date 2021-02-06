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
            \Graphpinator\Argument\Argument::create(
                'hue',
                \Graphpinator\Container\Container::Int()->notNull(),
            )->addDirective(
                \Graphpinator\Container\Container::directiveIntConstraint(),
                ['min' => 0, 'max' => 360],
            ),
            \Graphpinator\Argument\Argument::create(
                'saturation',
                \Graphpinator\Container\Container::Int()->notNull(),
            )->addDirective(
                \Graphpinator\Container\Container::directiveIntConstraint(),
                ['min' => 0, 'max' => 100],
            ),
            \Graphpinator\Argument\Argument::create(
                'lightness',
                \Graphpinator\Container\Container::Int()->notNull(),
            )->addDirective(
                \Graphpinator\Container\Container::directiveIntConstraint(),
                ['min' => 0, 'max' => 100],
            ),
        ]);
    }
}
