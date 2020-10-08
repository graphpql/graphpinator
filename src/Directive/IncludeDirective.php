<?php

declare(strict_types = 1);

namespace Graphpinator\Directive;

final class IncludeDirective extends \Graphpinator\Directive\ExecutableDirective
{
    protected const NAME = 'include';
    protected const DESCRIPTION = 'Built-in include directive.';

    public function __construct()
    {
        parent::__construct(
            [
                ExecutableDirectiveLocation::FIELD,
                ExecutableDirectiveLocation::FRAGMENT_SPREAD,
                ExecutableDirectiveLocation::INLINE_FRAGMENT,
            ],
            false,
            new \Graphpinator\Argument\ArgumentSet([
                new \Graphpinator\Argument\Argument('if', \Graphpinator\Container\Container::Boolean()->notNull()),
            ]),
            static function (bool $if) : string {
                return $if
                    ? DirectiveResult::NONE
                    : DirectiveResult::SKIP;
            },
        );
    }
}
