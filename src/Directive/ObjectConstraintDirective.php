<?php

declare(strict_types = 1);

namespace Graphpinator\Directive;

final class ObjectConstraintDirective extends \Graphpinator\Directive\TypeSystemDirective
{
    protected const NAME = 'objectConstraint';
    protected const DESCRIPTION = 'Graphpinator objectConstraint directive.';

    public function __construct()
    {
        parent::__construct(
            [
                TypeSystemDirectiveLocation::INPUT_OBJECT,
                TypeSystemDirectiveLocation::INTERFACE,
                TypeSystemDirectiveLocation::OBJECT,
            ],
            false,
            new \Graphpinator\Argument\ArgumentSet([
                new \Graphpinator\Argument\Argument('atLeastOne', \Graphpinator\Container\Container::String()->notNull()->list()),
                new \Graphpinator\Argument\Argument('exactlyOne', \Graphpinator\Container\Container::String()->notNull()->list()),
            ]),
        );
    }
}
