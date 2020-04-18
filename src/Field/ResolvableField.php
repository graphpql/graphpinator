<?php

declare(strict_types = 1);

namespace Graphpinator\Field;

use Graphpinator\Resolver\FieldResult;

final class ResolvableField extends Field
{
    private \Closure $resolveFunction;

    public function __construct(string $name, \Graphpinator\Type\Contract\Outputable $type, callable $resolveFunction, ?\Graphpinator\Argument\ArgumentSet $arguments = null)
    {
        parent::__construct($name, $type, $arguments);
        $this->resolveFunction = $resolveFunction;
    }

    public function resolve(FieldResult $parentValue, \Graphpinator\Resolver\ArgumentValueSet $arguments) : FieldResult
    {
        $result = \call_user_func($this->resolveFunction, $parentValue->getResult()->getRawValue(), $arguments);

        if ($result instanceof FieldResult) {
            if ($result->getType()->isInstanceOf($this->type)) {
                return $result;
            }

            throw new \Graphpinator\Exception\Resolver\FieldResultTypeMismatch();
        }

        if ($this->type->getNamedType() instanceof \Graphpinator\Type\Contract\ConcreteDefinition) {
            return FieldResult::fromRaw($this->type, $result);
        }

        throw new \Graphpinator\Exception\Resolver\FieldResultAbstract();
    }
}
