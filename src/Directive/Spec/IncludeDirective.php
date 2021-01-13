<?php

declare(strict_types = 1);

namespace Graphpinator\Directive\Spec;

final class IncludeDirective extends \Graphpinator\Directive\ExecutableDirective
{
    protected const NAME = 'include';
    protected const DESCRIPTION = 'Built-in include directive.';

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
                    ? \Graphpinator\Directive\DirectiveResult::NONE
                    : \Graphpinator\Directive\DirectiveResult::SKIP;
            },
            null,
        );
    }

    public function validateType(\Graphpinator\Type\Contract\Outputable $type) : bool
    {
        return true;
    }
}
