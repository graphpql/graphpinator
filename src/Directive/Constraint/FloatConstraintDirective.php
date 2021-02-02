<?php

declare(strict_types = 1);

namespace Graphpinator\Directive\Constraint;

final class FloatConstraintDirective extends LeafConstraintDirective
{
    protected const NAME = 'floatConstraint';
    protected const DESCRIPTION = 'Graphpinator floatConstraint directive.';

    public function __construct()
    {
        parent::__construct(
            new \Graphpinator\Argument\ArgumentSet([
                new \Graphpinator\Argument\Argument('min', \Graphpinator\Container\Container::Float()),
                new \Graphpinator\Argument\Argument('max', \Graphpinator\Container\Container::Float()),
                new \Graphpinator\Argument\Argument('oneOf', \Graphpinator\Container\Container::Float()->notNull()->list()),
            ]),
        );
    }
}
