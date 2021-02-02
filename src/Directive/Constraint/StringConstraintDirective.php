<?php

declare(strict_types = 1);

namespace Graphpinator\Directive\Constraint;

final class StringConstraintDirective extends LeafConstraintDirective
{
    protected const NAME = 'stringConstraint';
    protected const DESCRIPTION = 'Graphpinator stringConstraint directive.';

    public function __construct()
    {
        parent::__construct(
            new \Graphpinator\Argument\ArgumentSet([
                new \Graphpinator\Argument\Argument('minLength', \Graphpinator\Container\Container::Int()),
                new \Graphpinator\Argument\Argument('maxLength', \Graphpinator\Container\Container::Int()),
                new \Graphpinator\Argument\Argument('regex', \Graphpinator\Container\Container::String()),
                new \Graphpinator\Argument\Argument('oneOf', \Graphpinator\Container\Container::String()->notNull()->list()),
            ]),
        );
    }
}
