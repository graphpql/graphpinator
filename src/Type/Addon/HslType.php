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
            (new \Graphpinator\Field\ResolvableField(
                'hue',
                \Graphpinator\Container\Container::Int()->notNull(),
                static function (\stdClass $hsl) : int {
                    return $hsl->hue;
                },
            ))->addConstraint(new \Graphpinator\Constraint\IntConstraint(0, 360)),
            (new \Graphpinator\Field\ResolvableField(
                'saturation',
                \Graphpinator\Container\Container::Int()->notNull(),
                static function (\stdClass $hsl) : int {
                    return $hsl->saturation;
                },
            ))->addConstraint(new \Graphpinator\Constraint\IntConstraint(0, 100)),
            (new \Graphpinator\Field\ResolvableField(
                'lightness',
                \Graphpinator\Container\Container::Int()->notNull(),
                static function (\stdClass $hsl) : int {
                    return $hsl->lightness;
                },
            ))->addConstraint(new \Graphpinator\Constraint\IntConstraint(0, 100)),
        ]);
    }

    public function validateNonNullValue(mixed $rawValue) : bool
    {
        return $rawValue instanceof \stdClass
            && \property_exists($rawValue, 'hue')
            && \property_exists($rawValue, 'saturation')
            && \property_exists($rawValue, 'lightness')
            && \is_int($rawValue->hue)
            && \is_int($rawValue->saturation)
            && \is_int($rawValue->lightness);
    }
}
