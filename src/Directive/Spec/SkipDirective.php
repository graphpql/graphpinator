<?php

declare(strict_types = 1);

namespace Graphpinator\Directive\Spec;

final class SkipDirective extends \Graphpinator\Directive\ExecutableDirective
{
    protected const NAME = 'skip';
    protected const DESCRIPTION = 'Built-in skip directive.';

    public function __construct()
    {
        parent::__construct(
            [
                \Graphpinator\Directive\ExecutableDirectiveLocation::FIELD,
                \Graphpinator\Directive\ExecutableDirectiveLocation::FRAGMENT_SPREAD,
                \Graphpinator\Directive\ExecutableDirectiveLocation::INLINE_FRAGMENT,
            ],
            false,
            new \Graphpinator\Argument\ArgumentSet([
                new \Graphpinator\Argument\Argument('if', \Graphpinator\Container\Container::Boolean()->notNull()),
            ]),
            static function (bool $if) : string {
                return $if
                    ? \Graphpinator\Directive\DirectiveResult::SKIP
                    : \Graphpinator\Directive\DirectiveResult::NONE;
            },
            null,
        );
    }
}
