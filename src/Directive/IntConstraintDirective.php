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
            ],
            false,
        );
    }
}
