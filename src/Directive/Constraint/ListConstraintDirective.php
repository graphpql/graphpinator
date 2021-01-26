<?php

declare(strict_types = 1);

namespace Graphpinator\Directive\Constraint;

final class ListConstraintDirective extends \Graphpinator\Directive\Directive implements \Graphpinator\Directive\TypeSystemDirective
{
    protected const NAME = 'listConstraint';
    protected const DESCRIPTION = 'Graphpinator listConstraint directive.';

    public function __construct()
    {
        parent::__construct(
            [
                \Graphpinator\Directive\TypeSystemDirectiveLocation::ARGUMENT_DEFINITION,
                \Graphpinator\Directive\TypeSystemDirectiveLocation::INPUT_FIELD_DEFINITION,
                \Graphpinator\Directive\TypeSystemDirectiveLocation::FIELD_DEFINITION,
            ],
            false,
            \Graphpinator\Container\Container::listConstraintInput()->getArguments(),
        );
    }
}
