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
                (new \Graphpinator\Field\ResolvableField(
                    'alpha',
                    \Graphpinator\Container\Container::Float()->notNull(),
                    static function (\stdClass $rgba) : float {
                        return $rgba->alpha;
                    },
                ))->addConstraint(new \Graphpinator\Constraint\FloatConstraint(0.0, 1.0)),
            ]),
        );
    }

    protected function validateNonNullValue($rawValue) : bool
    {
        return parent::validateNonNullValue($rawValue)
            && \property_exists($rawValue, 'alpha')
            && \is_float($rawValue->alpha);
    }
}
