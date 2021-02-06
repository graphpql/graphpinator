<?php

declare(strict_types = 1);

namespace Graphpinator\Field;

final class ResolvableField extends \Graphpinator\Field\Field
{
    private \Closure $resolveFn;

    public function __construct(string $name, \Graphpinator\Type\Contract\Outputable $type, callable $resolveFn)
    {
        parent::__construct($name, $type);
        $this->resolveFn = $resolveFn;
    }

    public static function create(string $name, \Graphpinator\Type\Contract\Outputable $type, ?callable $resolveFn = null) : self
    {
        return new self($name, $type, $resolveFn);
    }

    public function resolve(
        \Graphpinator\Value\ResolvedValue $parentValue,
        \Graphpinator\Normalizer\Field\Field $field,
    ) : \Graphpinator\Value\FieldValue
    {
        foreach ($this->directiveUsages as $directive) {
            $directive->getDirective()->resolveFieldDefinitionBefore($directive->getArgumentValues());
        }

        $arguments = $field->getArguments();
        $rawArguments = $arguments->getValuesForResolver();
        \array_unshift($rawArguments, $parentValue->getRawValue());
        $result = \call_user_func_array($this->resolveFn, $rawArguments);
        $value = $this->type->createResolvedValue($result);

        if (!$value->getType()->isInstanceOf($this->type)) {
            throw new \Graphpinator\Exception\Resolver\FieldResultTypeMismatch();
        }

        $fieldValue = $value instanceof \Graphpinator\Value\NullValue
            ? $value
            : $value->getType()->resolve($field->getFields(), $value);

        return new \Graphpinator\Value\FieldValue($this, $fieldValue);
    }
}
