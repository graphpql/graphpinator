<?php

declare(strict_types = 1);

namespace Graphpinator\Directive;

final class IntConstraintDirective extends \Graphpinator\Directive\TypeSystemDirective
{
    protected const NAME = 'intConstraint';
    protected const DESCRIPTION = 'Graphpinator intConstraint directive.';

    public function __construct()
    {
        parent::__construct(
            [
                TypeSystemDirectiveLocation::ARGUMENT_DEFINITION,
                TypeSystemDirectiveLocation::INPUT_FIELD_DEFINITION,
                TypeSystemDirectiveLocation::FIELD_DEFINITION,
            ],
            false,
            new \Graphpinator\Argument\ArgumentSet([
                new \Graphpinator\Argument\Argument('min', \Graphpinator\Container\Container::Int()),
                new \Graphpinator\Argument\Argument('max', \Graphpinator\Container\Container::Int()),
                new \Graphpinator\Argument\Argument('oneOf', \Graphpinator\Container\Container::Int()->notNull()->list()),
            ]),
        );
    }
}
