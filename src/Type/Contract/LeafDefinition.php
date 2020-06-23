<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Contract;

abstract class LeafDefinition extends \Graphpinator\Type\Contract\ConcreteDefinition implements
    \Graphpinator\Type\Contract\Inputable,
    \Graphpinator\Type\Contract\Resolvable
{
    use \Graphpinator\Type\Contract\TResolvable;

    final public function resolve(
        ?\Graphpinator\Normalizer\FieldSet $requestedFields,
        \Graphpinator\Resolver\FieldResult $parentResult
    ) : \Graphpinator\Resolver\Value\ValidatedValue
    {
        if ($requestedFields instanceof \Graphpinator\Normalizer\FieldSet) {
            throw new \Graphpinator\Exception\Resolver\SelectionOnLeaf();
        }

        return $parentResult->getResult();
    }

    //@phpcs:ignore SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingAnyTypeHint
    final public function applyDefaults($value)
    {
        return $value;
    }

    final public function createValue($rawValue) : \Graphpinator\Resolver\Value\ValidatedValue
    {
        return \Graphpinator\Resolver\Value\LeafValue::create($rawValue, $this);
    }
}
