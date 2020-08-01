<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Contract;

trait TResolvable
{
    public function validateValue($rawValue) : void
    {
        if ($rawValue === null) {
            return;
        }

        if (!$this->validateNonNullValue($rawValue)) {
            throw new \Graphpinator\Exception\Type\InvalidResolvedValue($this->getName());
        }
    }

    abstract protected function validateNonNullValue($rawValue) : bool;
}
