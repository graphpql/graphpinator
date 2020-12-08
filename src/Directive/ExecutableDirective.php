<?php

declare(strict_types = 1);

namespace Graphpinator\Directive;

abstract class ExecutableDirective extends \Graphpinator\Directive\Directive
{
    private ?\Closure $resolveFnBefore;
    private ?\Closure $resolveFnAfter;

    public function __construct(
        array $locations,
        bool $repeatable,
        \Graphpinator\Argument\ArgumentSet $arguments,
        ?callable $resolveFnBefore,
        ?callable $resolveFnAfter,
    )
    {
        parent::__construct($locations, $repeatable, $arguments);

        $this->resolveFnBefore = $resolveFnBefore;
        $this->resolveFnAfter = $resolveFnAfter;
    }

    public function resolveBefore(\Graphpinator\Value\ArgumentValueSet $arguments) : string
    {
        if (!$this->resolveFnBefore instanceof \Closure) {
            return DirectiveResult::NONE;
        }

        $result = \call_user_func_array($this->resolveFnBefore, $arguments->getRawValues());

        if (\is_string($result) && \array_key_exists($result, DirectiveResult::ENUM)) {
            return $result;
        }

        throw new \Graphpinator\Exception\Resolver\InvalidDirectiveResult();
    }

    public function resolveAfter(
        \Graphpinator\Field\FieldValue $fieldValue,
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : string
    {
        if (!$this->resolveFnAfter instanceof \Closure) {
            return DirectiveResult::NONE;
        }

        $rawArguments = $arguments->getRawValues();
        \array_unshift($rawArguments, $fieldValue->getValue());
        $result = \call_user_func_array($this->resolveFnAfter, $rawArguments);

        if (\is_string($result) && \array_key_exists($result, DirectiveResult::ENUM)) {
            return $result;
        }

        throw new \Graphpinator\Exception\Resolver\InvalidDirectiveResult();
    }
}
