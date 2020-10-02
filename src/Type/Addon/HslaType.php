<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

final class HslaType extends \Graphpinator\Type\Addon\HslType
{
    protected const NAME = 'Hsla';
    protected const DESCRIPTION = 'This add on scalar validates hsla array input with keys and its values -
    hue (0-360), saturation (0-100), lightness (0-100), alpha (0-1).
    Examples - ["hue" => 180, "saturation\" => 50, "lightness" => 50, "alpha" => 0.5],
               ["hue" => 360, "saturation\" => 100, "lightness" => 100, "alpha" => 1.0],
               ["hue" => 0, "saturation" => 0, "lightness" => 0, "alpha" => 0.0]';

    protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
    {
        return parent::getFieldDefinition()->merge(
            new \Graphpinator\Field\ResolvableFieldSet([
                new \Graphpinator\Field\ResolvableField(
                    'alpha',
                    \Graphpinator\Type\Container\Container::Float()->notNull(),
                    static function (\stdClass $hsla) {
                        return $hsla->alpha;
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
