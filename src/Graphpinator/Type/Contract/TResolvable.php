<?php

declare(strict_types = 1);

namespace Infinityloop\Graphpinator\Type\Contract;

trait TResolvable
{
    public function validateValue($rawValue) : void
    {
        if ($rawValue === null) {
            return;
        }

        $this->validateNonNullValue($rawValue);
    }

    abstract protected function validateNonNullValue($rawValue);
}
