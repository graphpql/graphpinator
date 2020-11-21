<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

class RgbType extends \Graphpinator\Type\Type
{
    protected const NAME = 'Rgb';
    protected const DESCRIPTION = 'Rgb type - type representing the RGB color model.';

    public function validateNonNullValue($rawValue) : bool
    {
        return $rawValue instanceof \stdClass
            && \property_exists($rawValue, 'red')
            && \property_exists($rawValue, 'green')
            && \property_exists($rawValue, 'blue')
            && \is_int($rawValue->red)
            && \is_int($rawValue->green)
            && \is_int($rawValue->blue);
    }

    protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
    {
        return new \Graphpinator\Field\ResolvableFieldSet([
            (new \Graphpinator\Field\ResolvableField(
                'red',
                \Graphpinator\Container\Container::Int()->notNull(),
                static function (\stdClass $rgb) : int {
                    return $rgb->red;
                },
            ))->addConstraint(new \Graphpinator\Constraint\IntConstraint(0, 255)),
            (new \Graphpinator\Field\ResolvableField(
                'green',
                \Graphpinator\Container\Container::Int()->notNull(),
                static function (\stdClass $rgb) : int {
                    return $rgb->green;
                },
            ))->addConstraint(new \Graphpinator\Constraint\IntConstraint(0, 255)),
            (new \Graphpinator\Field\ResolvableField(
                'blue',
                \Graphpinator\Container\Container::Int()->notNull(),
                static function (\stdClass $rgb) : int {
                    return $rgb->blue;
                },
            ))->addConstraint(new \Graphpinator\Constraint\IntConstraint(0, 255)),
        ]);
    }
}
