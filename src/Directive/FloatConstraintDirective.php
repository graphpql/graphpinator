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
        );
    }
}
