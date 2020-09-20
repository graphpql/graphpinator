<?php

declare(strict_types = 1);

namespace Graphpinator\Directive;

final class InputConstraintDirective extends \Graphpinator\Directive\TypeSystemDirective
{
    protected const NAME = 'inputConstraint';
    protected const DESCRIPTION = 'Graphpinator inputConstraint directive.';

    public function __construct()
    {
        parent::__construct(
            [
                TypeSystemDirectiveLocation::INPUT_OBJECT,
            ],
            false,
        );
    }
}
