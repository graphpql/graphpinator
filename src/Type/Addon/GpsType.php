<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

final class GpsType extends \Graphpinator\Type\Type
{
    protected const NAME = 'Gps';
    protected const DESCRIPTION = 'Gps type - latitude and longitude.';

    protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
    {
        return new \Graphpinator\Field\ResolvableFieldSet([
            (new \Graphpinator\Field\ResolvableField(
                'lat',
                \Graphpinator\Container\Container::Float()->notNull(),
                static function(\stdClass $gps) : float {
                    return $gps->lat;
                },
            ))->addConstraint(new \Graphpinator\Constraint\FloatConstraint(-90.0, 90.0)),
            (new \Graphpinator\Field\ResolvableField(
                'lng',
                \Graphpinator\Container\Container::Float()->notNull(),
                static function(\stdClass $gps) : float {
                    return $gps->lng;
                },
            ))->addConstraint(new \Graphpinator\Constraint\FloatConstraint(-180.0, 180.0)),
        ]);
    }

    public function validateNonNullValue($rawValue) : bool
    {
        return $rawValue instanceof \stdClass
            && \property_exists($rawValue, 'lat')
            && \property_exists($rawValue, 'lng')
            && \is_float($rawValue->lat)
            && \is_float($rawValue->lng);
    }
}
