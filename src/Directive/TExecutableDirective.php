<?php

declare(strict_types = 1);

namespace Graphpinator\Directive;

trait TExecutableDirective
{
    protected ?\Closure $fieldBeforeFn = null;
    protected ?\Closure $fieldAfterFn = null;

    abstract public function validateType(\Graphpinator\Type\Contract\Definition $type) : bool;

    final public function resolveFieldBefore(
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : string
    {
        if (!$this->fieldBeforeFn instanceof \Closure) {
            return FieldDirectiveResult::NONE;
        }

        $result = \call_user_func_array($this->fieldBeforeFn, $arguments->getValuesForResolver());

        if (\is_string($result) && \array_key_exists($result, FieldDirectiveResult::ENUM)) {
            return $result;
        }

        throw new \Graphpinator\Exception\Resolver\InvalidDirectiveResult();
    }

    final public function resolveFieldAfter(
        \Graphpinator\Field\FieldValue $fieldValue,
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : string
    {
        if (!$this->fieldAfterFn instanceof \Closure) {
            return FieldDirectiveResult::NONE;
        }

        $rawArguments = $arguments->getValuesForResolver();
        \array_unshift($rawArguments, $fieldValue->getValue());
        $result = \call_user_func_array($this->fieldAfterFn, $rawArguments);

        if (\is_string($result) && \array_key_exists($result, FieldDirectiveResult::ENUM)) {
            return $result;
        }

        throw new \Graphpinator\Exception\Resolver\InvalidDirectiveResult();
    }
}
