<?php

declare(strict_types = 1);

namespace Graphpinator\Field;

use \Graphpinator\Resolver\FieldResult;

final class ResolvableField extends \Graphpinator\Field\Field
{
    private \Closure $resolveFn;

    public function __construct(
        string $name,
        \Graphpinator\Type\Contract\Outputable $type,
        callable $resolveFn,
        ?\Graphpinator\Argument\ArgumentSet $arguments = null
    )
    {
        parent::__construct($name, $type, $arguments);
        $this->resolveFn = $resolveFn;
    }

    public function resolve(FieldResult $parentValue, \Graphpinator\Resolver\ArgumentValueSet $arguments) : FieldResult
    {
        $args = $arguments->getRawArguments();
        \array_unshift($args, $parentValue->getResult()->getRawValue());

        $result = \call_user_func_array($this->resolveFn, $args);

        if (!$result instanceof FieldResult) {
            return FieldResult::fromRaw($this->type, $result);
        }

        if ($result->getType()->isInstanceOf($this->type)) {
            return $result;
        }

        throw new \Graphpinator\Exception\Resolver\FieldResultTypeMismatch();
    }
}
