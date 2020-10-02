<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

class HslType extends \Graphpinator\Type\Type
{
    protected const NAME = 'Hsl';
    protected const DESCRIPTION = 'This add on scalar validates hsl array input with keys and its values -
    hue (0-360), saturation (0-100), lightness (0-100).
    Examples - ["hue" => 180, "saturation\" => 50, "lightness" => 50],
               ["hue" => 360, "saturation\" => 100, "lightness" => 100],
               ["hue" => 0, "saturation" => 0, "lightness" => 0]';

    protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
    {
        return new \Graphpinator\Field\ResolvableFieldSet([
            new \Graphpinator\Field\ResolvableField(
                'hue',
                \Graphpinator\Type\Container\Container::Int()->notNull(),
                static function (\stdClass $hsl) {
                    return $hsl->hue;
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'saturation',
                \Graphpinator\Type\Container\Container::Int()->notNull(),
                static function (\stdClass $hsl) {
                    return $hsl->saturation;
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'lightness',
                \Graphpinator\Type\Container\Container::Int()->notNull(),
                static function (\stdClass $hsl) {
                    return $hsl->lightness;
                },
            ),
        ]);
    }

    protected function validateNonNullValue($rawValue) : bool
    {
        return $rawValue instanceof \stdClass
            && \array_key_exists('hue', (array) $rawValue)
            && \array_key_exists('saturation', (array) $rawValue)
            && \array_key_exists('lightness', (array) $rawValue)
            && \is_int($rawValue->hue)
            && \is_int($rawValue->saturation)
            && \is_int($rawValue->lightness)
            && $rawValue->hue <= 360
            && $rawValue->hue >= 0
            && $rawValue->saturation <= 100
            && $rawValue->saturation >= 0
            && $rawValue->lightness <= 100
            && $rawValue->lightness >= 0;
    }
}
