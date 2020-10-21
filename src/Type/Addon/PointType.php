<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

final class PointType extends \Graphpinator\Type\Scalar\ScalarType
{
    protected const NAME = 'Point';
    protected const DESCRIPTION = 'Point type - float which contains points x and y.';

    protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
    {
        return new \Graphpinator\Field\ResolvableFieldSet([
            new \Graphpinator\Field\ResolvableField(
                'x',
                \Graphpinator\Container\Container::Float()->notNull(),
                static function(\stdClass $point) {
                    return $point->x;
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'y',
                \Graphpinator\Container\Container::Float()->notNull(),
                static function(\stdClass $point) {
                    return $point->y;
                },
            ),
        ]);
    }

    protected function validateNonNullValue($rawValue) : bool
    {
        return $rawValue instanceof \stdClass
            && \property_exists($rawValue, 'x')
            && \property_exists($rawValue, 'y')
            && \is_float($rawValue->x)
            && \is_float($rawValue->y);
    }
}
