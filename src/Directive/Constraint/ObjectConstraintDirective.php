<?php

declare(strict_types = 1);

namespace Graphpinator\Directive\Constraint;

final class ObjectConstraintDirective extends \Graphpinator\Directive\TypeSystemDirective
{
    protected const NAME = 'objectConstraint';
    protected const DESCRIPTION = 'Graphpinator objectConstraint directive.';

    public function __construct()
    {
        parent::__construct(
            [
                \Graphpinator\Directive\TypeSystemDirectiveLocation::INPUT_OBJECT,
                \Graphpinator\Directive\TypeSystemDirectiveLocation::INTERFACE,
                \Graphpinator\Directive\TypeSystemDirectiveLocation::OBJECT,
            ],
            false,
            new \Graphpinator\Argument\ArgumentSet([
                new \Graphpinator\Argument\Argument('atLeastOne', \Graphpinator\Container\Container::String()->notNull()->list()),
                new \Graphpinator\Argument\Argument('exactlyOne', \Graphpinator\Container\Container::String()->notNull()->list()),
            ]),
        );
    }
}
