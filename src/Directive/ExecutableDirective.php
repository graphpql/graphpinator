<?php

declare(strict_types = 1);

namespace Graphpinator\Directive;

abstract class ExecutableDirective extends \Graphpinator\Directive\Directive
{
    private \Closure $resolveFn;

    public function __construct(array $locations, bool $repeatable, \Graphpinator\Argument\ArgumentSet $arguments, callable $resolveFn)
    {
        parent::__construct($locations, $repeatable, $arguments);

        $this->resolveFn = $resolveFn;
    }

    public function resolve(\Graphpinator\Normalizer\Value\ArgumentValueSet $arguments) : string
    {
        $result = \call_user_func_array($this->resolveFn, $arguments->getRawValues());

        if (\is_string($result) && \array_key_exists($result, DirectiveResult::ENUM)) {
            return $result;
        }

        throw new \Graphpinator\Exception\Resolver\InvalidDirectiveResult();
    }
}
