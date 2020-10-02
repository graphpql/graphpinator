<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

final class RgbaType extends \Graphpinator\Type\Addon\RgbType
{
    protected const NAME = 'Rgba';
    protected const DESCRIPTION = 'This add on scalar validates rgba array input with keys and its values -
    red (0-255), green (0-255), blue (0-255), alpha (0-1).
    Examples - ["red" => 100, "green" => 50,  "blue" => 50,  "alpha" => 0.5],
               ["red" => 255, "green" => 255, "blue" => 255, "alpha" => 1.0],
               ["red" => 0,   "green" => 0,   "blue" => 0,   "alpha" => 0.0]';

    protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
    {
        return parent::getFieldDefinition()->merge(
            new \Graphpinator\Field\ResolvableFieldSet([
                new \Graphpinator\Field\ResolvableField(
                    'alpha',
                    \Graphpinator\Type\Container\Container::Float()->notNull(),
                    static function (\stdClass $rgba) {
                        return $rgba->alpha;
                    },
                ),
            ]),
        );
    }

    protected function validateNonNullValue($rawValue) : bool
    {
        return parent::validateNonNullValue($rawValue)
            && \property_exists($rawValue, 'alpha')
            && \is_float($rawValue->alpha)
            && $rawValue->alpha <= 1
            && $rawValue->alpha >= 0;
    }
}
