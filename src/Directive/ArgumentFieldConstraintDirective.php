<?php

declare(strict_types = 1);

namespace Graphpinator\Directive;

final class ArgumentFieldConstraintDirective extends \Graphpinator\Directive\TypeSystemDirective
{
    protected const NAME = 'argumentFieldConstraint';
    protected const DESCRIPTION = 'Graphpinator argumentFieldConstraint directive.';

    public function __construct()
    {
        parent::__construct(
            [
                TypeSystemDirectiveLocation::ARGUMENT_DEFINITION,
                TypeSystemDirectiveLocation::FIELD_DEFINITION,
                TypeSystemDirectiveLocation::INPUT_FIELD_DEFINITION,
            ],
            false,
            new \Graphpinator\Argument\ArgumentSet([]),
        );
    }
}
