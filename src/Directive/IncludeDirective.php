<?php

declare(strict_types = 1);

namespace Graphpinator\Directive;

final class IncludeDirective extends Directive
{
    protected const NAME = 'include';
    protected const DESCRIPTION = 'Built-in include directive.';

    public function __construct()
    {
        parent::__construct(
            new \Graphpinator\Argument\ArgumentSet([
                new \Graphpinator\Argument\Argument('if', \Graphpinator\Type\Container\Container::Boolean()->notNull()),
            ]),
            static function (\Graphpinator\Resolver\ArgumentValueSet $arguments) : string {
                return $arguments->offsetGet('if')->getRawValue()
                    ? DirectiveResult::NONE
                    : DirectiveResult::SKIP;
            },
            [
                DirectiveLocation::FIELD,
                DirectiveLocation::FRAGMENT_SPREAD,
                DirectiveLocation::INLINE_FRAGMENT,
            ],
            false,
        );
    }
}
