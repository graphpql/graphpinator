<?php

declare(strict_types = 1);

namespace Graphpinator\Directive;

final class DeprecatedDirective extends \Graphpinator\Directive\TypeSystemDirective
{
    protected const NAME = 'deprecated';
    protected const DESCRIPTION = 'Built-in deprecated directive.';

    public function __construct()
    {
        parent::__construct(
            [
                TypeSystemDirectiveLocation::FIELD_DEFINITION,
                TypeSystemDirectiveLocation::ENUM_VALUE,
            ],
            false,
            new \Graphpinator\Argument\ArgumentSet([
                new \Graphpinator\Argument\Argument('reason', \Graphpinator\Container\Container::String()),
            ]),
        );
    }
}
