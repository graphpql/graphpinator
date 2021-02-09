<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

class HslType extends \Graphpinator\Type\Type
{
    protected const NAME = 'Hsl';
    protected const DESCRIPTION = 'Hsl type - type representing the HSL color model.';

    public function __construct(
        protected \Graphpinator\Directive\Constraint\ConstraintDirectiveAccessor $constraintDirectiveAccessor,
    )
    {
        parent::__construct();
    }

    protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
    {
        return new \Graphpinator\Field\ResolvableFieldSet([
            \Graphpinator\Field\ResolvableField::create(
                'hue',
                \Graphpinator\Container\Container::Int()->notNull(),
                static function (\stdClass $hsl) : int {
                    return $hsl->hue;
                },
            )->addDirective(
                $this->constraintDirectiveAccessor->getInt(),
                ['min' => 0, 'max' => 360],
            ),
            \Graphpinator\Field\ResolvableField::create(
                'saturation',
                \Graphpinator\Container\Container::Int()->notNull(),
                static function (\stdClass $hsl) : int {
                    return $hsl->saturation;
                },
            )->addDirective(
                $this->constraintDirectiveAccessor->getInt(),
                ['min' => 0, 'max' => 100],
            ),
            \Graphpinator\Field\ResolvableField::create(
                'lightness',
                \Graphpinator\Container\Container::Int()->notNull(),
                static function (\stdClass $hsl) : int {
                    return $hsl->lightness;
                },
            )->addDirective(
                $this->constraintDirectiveAccessor->getInt(),
                ['min' => 0, 'max' => 100],
            ),
        ]);
    }

    public function validateNonNullValue(mixed $rawValue) : bool
    {
        return $rawValue instanceof \stdClass
            && \property_exists($rawValue, 'hue')
            && \property_exists($rawValue, 'saturation')
            && \property_exists($rawValue, 'lightness')
            && \is_int($rawValue->hue)
            && \is_int($rawValue->saturation)
            && \is_int($rawValue->lightness);
    }
}
