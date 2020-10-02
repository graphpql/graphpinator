<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

class RgbType extends \Graphpinator\Type\Type
{
    protected const NAME = 'Rgb';
    protected const DESCRIPTION = 'This add on scalar validates rgb array input with keys and its values -
    red (0-255), green (0-255), blue (0-255).
    Examples - ["red" => 100, "green" => 50, "blue" => 50, "alpha" => 0.5],
               ["red" => 255, "green" => 255, "blue" => 255, "alpha" => 1.0],
               ["red" => 0, "green" => 0, "blue" => 0, "alpha" => 0.0]';

    protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
    {
        return new \Graphpinator\Field\ResolvableFieldSet([
            new \Graphpinator\Field\ResolvableField(
                'red',
                \Graphpinator\Type\Container\Container::Int()->notNull(),
                static function (\stdClass $rgb) {
                    return $rgb->red;
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'green',
                \Graphpinator\Type\Container\Container::Int()->notNull(),
                static function (\stdClass $rgb) {
                    return $rgb->green;
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'blue',
                \Graphpinator\Type\Container\Container::Int()->notNull(),
                static function (\stdClass $rgb) {
                    return $rgb->blue;
                },
            ),
        ]);
    }

    protected function validateNonNullValue($rawValue) : bool
    {
        return $rawValue instanceof \stdClass
            && \array_key_exists('red', (array) $rawValue)
            && \array_key_exists('green', (array) $rawValue)
            && \array_key_exists('blue', (array) $rawValue)
            && \is_int($rawValue->red)
            && \is_int($rawValue->green)
            && \is_int($rawValue->blue)
            && $rawValue->red <= 255
            && $rawValue->red >= 0
            && $rawValue->green <= 255
            && $rawValue->green >= 0
            && $rawValue->blue <= 255
            && $rawValue->blue >= 0;
    }
}
