<?php

declare(strict_types = 1);

namespace Graphpinator\Field;

use Graphpinator\Value\FieldValue;

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

    public function resolve(FieldValue $parentValue, \Graphpinator\Resolver\ArgumentValueSet $arguments) : FieldValue
    {
        $args = $arguments->getRawValues();
        \array_unshift($args, $parentValue->getValue()->getRawValue());

        $result = \call_user_func_array($this->resolveFn, $args);

        if (!$result instanceof FieldValue) {
            return new FieldValue($this->type, $result);
        }

        if ($result->getField()->getType()->isInstanceOf($this->type)) {
            return $result;
        }

        throw new \Graphpinator\Exception\Resolver\FieldResultTypeMismatch();
    }
}
