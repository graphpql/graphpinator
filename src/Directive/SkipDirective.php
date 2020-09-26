<?php

declare(strict_types = 1);

namespace Graphpinator\Directive;

final class SkipDirective extends \Graphpinator\Directive\ExecutableDirective
{
    protected const NAME = 'skip';
    protected const DESCRIPTION = 'Built-in skip directive.';

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
                new \Graphpinator\Argument\Argument('if', \Graphpinator\Type\Container\Container::Boolean()->notNull()),
            ]),
            static function (bool $if) : string {
                return $if
                    ? DirectiveResult::SKIP
                    : DirectiveResult::NONE;
            },
        );
    }
}
