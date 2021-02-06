<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

class RgbInput extends \Graphpinator\Type\InputType
{
    protected const NAME = 'RgbInput';
    protected const DESCRIPTION = 'Rgb input - input for the RGB color model.';

    protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
    {
        return new \Graphpinator\Argument\ArgumentSet([
            \Graphpinator\Argument\Argument::create(
                'red',
                \Graphpinator\Container\Container::Int()->notNull(),
            )->addDirective(
                \Graphpinator\Container\Container::directiveIntConstraint(),
                ['min' => 0, 'max' => 255],
            ),
            \Graphpinator\Argument\Argument::create(
                'green',
                \Graphpinator\Container\Container::Int()->notNull(),
            )->addDirective(
                \Graphpinator\Container\Container::directiveIntConstraint(),
                ['min' => 0, 'max' => 255],
            ),
            \Graphpinator\Argument\Argument::create(
                'blue',
                \Graphpinator\Container\Container::Int()->notNull(),
            )->addDirective(
                \Graphpinator\Container\Container::directiveIntConstraint(),
                ['min' => 0, 'max' => 255],
            ),
        ]);
    }
}
