<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

final class RgbaType extends \Graphpinator\Type\Addon\RgbType
{
    protected const NAME = 'Rgba';
    protected const DESCRIPTION = 'Rgba type - type representing the RGB color model with added alpha (transparency).';

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
