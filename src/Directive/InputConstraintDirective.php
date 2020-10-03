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
            new \Graphpinator\Argument\ArgumentSet([
                new \Graphpinator\Argument\Argument('atLeastOne', \Graphpinator\Container\Container::String()->notNull()->list()),
                new \Graphpinator\Argument\Argument('exactlyOne', \Graphpinator\Container\Container::String()->notNull()->list()),
            ]),
        );
    }
}
