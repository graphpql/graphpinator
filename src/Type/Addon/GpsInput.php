<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

final class GpsInput extends \Graphpinator\Type\InputType
{
    protected const NAME = 'GpsInput';
    protected const DESCRIPTION = 'Gps input - input for the GPS.';

    public function __construct(
        private \Graphpinator\Directive\Constraint\ConstraintDirectiveAccessor $constraintDirectiveAccessor,
    )
    {
        parent::__construct();
    }

    protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
    {
        return new \Graphpinator\Argument\ArgumentSet([
            \Graphpinator\Argument\Argument::create(
                'lat',
                \Graphpinator\Container\Container::Float()->notNull(),
            )->addDirective(
                $this->constraintDirectiveAccessor->getFloat(),
                ['min' => -90.0, 'max' => 90.0],
            ),
            \Graphpinator\Argument\Argument::create(
                'lng',
                \Graphpinator\Container\Container::Float()->notNull(),
            )->addDirective(
                $this->constraintDirectiveAccessor->getFloat(),
                ['min' => -180.0, 'max' => 180.0],
            ),
        ]);
    }
}
