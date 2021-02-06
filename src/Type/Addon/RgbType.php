<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

class RgbType extends \Graphpinator\Type\Type
{
    protected const NAME = 'Rgb';
    protected const DESCRIPTION = 'Rgb type - type representing the RGB color model.';

    protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
    {
        return new \Graphpinator\Field\ResolvableFieldSet([
            \Graphpinator\Field\ResolvableField::create(
                'red',
                \Graphpinator\Container\Container::Int()->notNull(),
                static function (\stdClass $rgb) : int {
                    return $rgb->red;
                },
            )->addDirective(
                \Graphpinator\Container\Container::directiveIntConstraint(),
                ['min' => 0, 'max' => 255],
            ),
            \Graphpinator\Field\ResolvableField::create(
                'green',
                \Graphpinator\Container\Container::Int()->notNull(),
                static function (\stdClass $rgb) : int {
                    return $rgb->green;
                },
            )->addDirective(
                \Graphpinator\Container\Container::directiveIntConstraint(),
                ['min' => 0, 'max' => 255],
            ),
            \Graphpinator\Field\ResolvableField::create(
                'blue',
                \Graphpinator\Container\Container::Int()->notNull(),
                static function (\stdClass $rgb) : int {
                    return $rgb->blue;
                },
            )->addDirective(
                \Graphpinator\Container\Container::directiveIntConstraint(),
                ['min' => 0, 'max' => 255],
            ),
        ]);
    }

    public function validateNonNullValue(mixed $rawValue) : bool
    {
        return $rawValue instanceof \stdClass
            && \property_exists($rawValue, 'red')
            && \property_exists($rawValue, 'green')
            && \property_exists($rawValue, 'blue')
            && \is_int($rawValue->red)
            && \is_int($rawValue->green)
            && \is_int($rawValue->blue);
    }
}
