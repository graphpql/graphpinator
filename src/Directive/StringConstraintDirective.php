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
                TypeSystemDirectiveLocation::FIELD_DEFINITION,
            ],
            false,
            new \Graphpinator\Argument\ArgumentSet([
                new \Graphpinator\Argument\Argument('minLength', \Graphpinator\Type\Container\Container::Int()),
                new \Graphpinator\Argument\Argument('maxLength', \Graphpinator\Type\Container\Container::Int()),
                new \Graphpinator\Argument\Argument('regex', \Graphpinator\Type\Container\Container::String()),
                new \Graphpinator\Argument\Argument('oneOf', \Graphpinator\Type\Container\Container::String()->notNull()->list()),
            ]),
        );
    }
}
