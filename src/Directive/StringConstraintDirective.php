<?php

declare(strict_types = 1);

namespace Graphpinator\Directive;

final class StringConstraintDirective extends \Graphpinator\Directive\TypeSystemDirective
{
    protected const NAME = 'stringConstraint';
    protected const DESCRIPTION = 'Graphpinator stringConstraint directive.';

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
