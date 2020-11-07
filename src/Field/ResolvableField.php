<?php

declare(strict_types = 1);

namespace Graphpinator\Field;

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

    public function resolve(
        \Graphpinator\Value\ResolvedValue $parentValue,
        \Graphpinator\Normalizer\Field $field
    ) : \Graphpinator\Field\FieldValue
    {
        $arguments = new \Graphpinator\Argument\ArgumentValueSet($field->getArguments(), $this->getArguments());
        $rawArguments = $arguments->getRawValues();
        \array_unshift($rawArguments, $parentValue->getRawValue());

        $result = \call_user_func_array($this->resolveFn, $rawArguments);
        $value = $this->type->createResolvedValue($result);

        if (!$value->getType()->isInstanceOf($this->type)) {
            throw new \Graphpinator\Exception\Resolver\FieldResultTypeMismatch();
        }

        $fieldValue = $value instanceof \Graphpinator\Value\NullValue
            ? $value
            : $value->getType()->resolve($field->getFields(), $value);

        return new \Graphpinator\Field\FieldValue($this, $fieldValue);
    }
}
