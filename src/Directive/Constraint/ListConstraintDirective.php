<?php

declare(strict_types = 1);

namespace Graphpinator\Directive\Constraint;

final class ListConstraintDirective extends LeafConstraintDirective
{
    protected const NAME = 'listConstraint';
    protected const DESCRIPTION = 'Graphpinator listConstraint directive.';

    public function __construct()
    {
        parent::__construct(\Graphpinator\Container\Container::listConstraintInput()->getArguments());
    }
}
