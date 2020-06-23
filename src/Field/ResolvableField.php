<?php

declare(strict_types = 1);

namespace Graphpinator\Field;

use \Graphpinator\Resolver\FieldResult;

final class ResolvableField extends \Graphpinator\Field\Field
{
    private \Closure $resolveFunction;

    public function __construct(
        string $name,
        \Graphpinator\Type\Contract\Outputable $type,
        callable $resolveFunction,
        ?\Graphpinator\Argument\ArgumentSet $arguments = null
    )
    {
        parent::__construct($name, $type, $arguments);
        $this->resolveFunction = $resolveFunction;
    }

    public function resolve(FieldResult $parentValue, \Graphpinator\Resolver\ArgumentValueSet $arguments) : FieldResult
    {
        $result = \call_user_func($this->resolveFunction, $parentValue->getResult()->getRawValue(), $arguments);

        if (!$result instanceof FieldResult) {
            return FieldResult::fromRaw($this->type, $result);
        }

        if ($result->getType()->isInstanceOf($this->type)) {
            return $result;
        }

        throw new \Graphpinator\Exception\Resolver\FieldResultTypeMismatch();
    }
}
