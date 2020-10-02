<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

class HslType extends \Graphpinator\Type\Type
{
    protected const NAME = 'Hsl';
    protected const DESCRIPTION = 'Hsl type - type representing the HSL color model.';

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
            && \property_exists($rawValue, 'hue')
            && \property_exists($rawValue, 'saturation')
            && \property_exists($rawValue, 'lightness')
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
