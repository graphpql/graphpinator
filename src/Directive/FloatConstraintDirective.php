<?php

declare(strict_types = 1);

namespace Graphpinator\Directive;

final class FloatConstraintDirective extends \Graphpinator\Directive\TypeSystemDirective
{
    protected const NAME = 'floatConstraint';
    protected const DESCRIPTION = 'Graphpinator floatConstraint directive.';

    public function __construct()
    {
        parent::__construct(
            [
                TypeSystemDirectiveLocation::ARGUMENT_DEFINITION,
                TypeSystemDirectiveLocation::INPUT_FIELD_DEFINITION,
            ],
            false,
            new \Graphpinator\Argument\ArgumentSet([
                new \Graphpinator\Argument\Argument('min', \Graphpinator\Type\Container\Container::Float()),
                new \Graphpinator\Argument\Argument('max', \Graphpinator\Type\Container\Container::Float()),
                new \Graphpinator\Argument\Argument('oneOf', \Graphpinator\Type\Container\Container::Float()->notNull()->list()),
            ]),
        );
    }
}
