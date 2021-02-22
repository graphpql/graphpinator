<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Contract;

abstract class LeafDefinition extends \Graphpinator\Type\Contract\ConcreteDefinition implements
    \Graphpinator\Type\Contract\Inputable,
    \Graphpinator\Type\Contract\Scopable
{
    abstract public function validateNonNullValue(mixed $rawValue) : bool;

    public function createInputedValue(string|int|float|bool|null|array|\stdClass|\Psr\Http\Message\UploadedFileInterface $rawValue) : \Graphpinator\Value\InputedValue
    {
        if ($rawValue === null) {
            return new \Graphpinator\Value\NullInputedValue($this);
        }

        return new \Graphpinator\Value\ScalarValue($this, $rawValue, true);
    }

    final public function createResolvedValue(mixed $rawValue) : \Graphpinator\Value\ResolvedValue
    {
        if ($rawValue === null) {
            return new \Graphpinator\Value\NullResolvedValue($this);
        }

        return new \Graphpinator\Value\ScalarValue($this, $rawValue, false);
    }

    final public function getField(string $name) : \Graphpinator\Field\Field
    {
        throw new \Graphpinator\Exception\Normalizer\SelectionOnLeaf();
    }
}
