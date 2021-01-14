<?php

declare(strict_types = 1);

namespace Graphpinator\Directive\Constraint;

final class StringConstraintDirective extends \Graphpinator\Directive\TypeSystemDirective
{
    protected const NAME = 'stringConstraint';
    protected const DESCRIPTION = 'Graphpinator stringConstraint directive.';

    public function __construct()
    {
        parent::__construct(
            [
                \Graphpinator\Directive\TypeSystemDirectiveLocation::ARGUMENT_DEFINITION,
                \Graphpinator\Directive\TypeSystemDirectiveLocation::INPUT_FIELD_DEFINITION,
                \Graphpinator\Directive\TypeSystemDirectiveLocation::FIELD_DEFINITION,
            ],
            false,
            new \Graphpinator\Argument\ArgumentSet([
                new \Graphpinator\Argument\Argument('minLength', \Graphpinator\Container\Container::Int()),
                new \Graphpinator\Argument\Argument('maxLength', \Graphpinator\Container\Container::Int()),
                new \Graphpinator\Argument\Argument('regex', \Graphpinator\Container\Container::String()),
                new \Graphpinator\Argument\Argument('oneOf', \Graphpinator\Container\Container::String()->notNull()->list()),
            ]),
        );
    }
}
