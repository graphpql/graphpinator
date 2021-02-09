<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

final class GpsType extends \Graphpinator\Type\Type
{
    protected const NAME = 'Gps';
    protected const DESCRIPTION = 'Gps type - latitude and longitude.';

    public function __construct(
        private \Graphpinator\Directive\Constraint\ConstraintDirectiveAccessor $constraintDirectiveAccessor,
    )
    {
        parent::__construct();
    }

    protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
    {
        return new \Graphpinator\Field\ResolvableFieldSet([
            \Graphpinator\Field\ResolvableField::create(
                'lat',
                \Graphpinator\Container\Container::Float()->notNull(),
                static function(\stdClass $gps) : float {
                    return $gps->lat;
                },
            )->addDirective(
                $this->constraintDirectiveAccessor->getFloat(),
                ['min' => -90.0, 'max' => 90.0],
            ),
            \Graphpinator\Field\ResolvableField::create(
                'lng',
                \Graphpinator\Container\Container::Float()->notNull(),
                static function(\stdClass $gps) : float {
                    return $gps->lng;
                },
            )->addDirective(
                $this->constraintDirectiveAccessor->getFloat(),
                ['min' => -180.0, 'max' => 180.0],
            ),
        ]);
    }

    public function validateNonNullValue(mixed $rawValue) : bool
    {
        return $rawValue instanceof \stdClass
            && \property_exists($rawValue, 'lat')
            && \property_exists($rawValue, 'lng')
            && \is_float($rawValue->lat)
            && \is_float($rawValue->lng);
    }
}
