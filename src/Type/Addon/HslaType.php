<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

final class HslaType extends \Graphpinator\Type\Addon\HslType
{
    protected const NAME = 'Hsla';
    protected const DESCRIPTION = 'Hsla type - type representing the HSL color model with added alpha (transparency).';

    protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
    {
        return parent::getFieldDefinition()->merge(
            new \Graphpinator\Field\ResolvableFieldSet([
                \Graphpinator\Field\ResolvableField::create(
                    'alpha',
                    \Graphpinator\Container\Container::Float()->notNull(),
                    static function (\stdClass $hsla) : float {
                        return $hsla->alpha;
                    },
                )->addDirective(
                    \Graphpinator\Container\Container::directiveFloatConstraint(),
                    ['min' => 0.0, 'max' => 1.0],
                ),
            ]),
        );
    }

    public function validateNonNullValue(mixed $rawValue) : bool
    {
        return parent::validateNonNullValue($rawValue)
            && \property_exists($rawValue, 'alpha')
            && \is_float($rawValue->alpha);
    }
}
