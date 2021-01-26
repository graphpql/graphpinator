<?php

declare(strict_types = 1);

namespace Graphpinator\Directive\Spec;

final class SkipDirective extends \Graphpinator\Directive\Directive
    implements \Graphpinator\Directive\Contract\ExecutableDefinition
{
    use \Graphpinator\Directive\TExecutableDirective;

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
        );

        $this->fieldBeforeFn = static function (bool $if) : string {
            return $if
                ? \Graphpinator\Directive\FieldDirectiveResult::SKIP
                : \Graphpinator\Directive\FieldDirectiveResult::NONE;
        };
    }

    public function validateType(\Graphpinator\Type\Contract\Definition $type) : bool
    {
        return true;
    }
}
