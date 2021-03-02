<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Contract;

abstract class LeafDefinition extends \Graphpinator\Type\Contract\ConcreteDefinition implements
    \Graphpinator\Type\Contract\Inputable,
    \Graphpinator\Type\Contract\Outputable
{
    abstract public function validateNonNullValue(mixed $rawValue) : bool;

    public function createInputedValue(string|int|float|bool|null|array|\stdClass|\Psr\Http\Message\UploadedFileInterface $rawValue) : \Graphpinator\Value\InputedValue
    {
        if ($rawValue === null) {
            return new \Graphpinator\Value\NullInputedValue($this);
        }

        return new \Graphpinator\Value\ScalarValue($this, $rawValue, true);
    }
}
