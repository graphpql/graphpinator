<?php

declare(strict_types = 1);

namespace Graphpinator\Directive\Spec;

final class DeprecatedDirective extends \Graphpinator\Directive\Directive
    implements \Graphpinator\Directive\Contract\TypeSystemDefinition
{
    protected const NAME = 'deprecated';
    protected const DESCRIPTION = 'Built-in deprecated directive.';

    public function __construct()
    {
        parent::__construct(
            [
                \Graphpinator\Directive\TypeSystemDirectiveLocation::FIELD_DEFINITION,
                \Graphpinator\Directive\TypeSystemDirectiveLocation::ENUM_VALUE,
            ],
            false,
            new \Graphpinator\Argument\ArgumentSet([
                new \Graphpinator\Argument\Argument('reason', \Graphpinator\Container\Container::String()),
            ]),
        );
    }
}
