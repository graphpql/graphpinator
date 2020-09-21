<?php

declare(strict_types = 1);

namespace Graphpinator\Directive;

final class ListConstraintDirective extends \Graphpinator\Directive\TypeSystemDirective
{
    protected const NAME = 'listConstraint';
    protected const DESCRIPTION = 'Graphpinator listConstraint directive.';

    public function __construct()
    {
        parent::__construct(
            [
                TypeSystemDirectiveLocation::ARGUMENT_DEFINITION,
                TypeSystemDirectiveLocation::INPUT_FIELD_DEFINITION,
            ],
            false,
            \Graphpinator\Type\Container\Container::listConstraintInput()->getArguments(),
        );
    }
}
