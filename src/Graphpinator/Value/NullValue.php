<?php

declare(strict_types = 1);

namespace PGQL\Value;

final class NullValue extends \PGQL\Value\ValidatedValue
{
    public function __construct(\PGQL\Type\Contract\Definition $type)
    {
        if ($type instanceof \PGQL\Type\NotNullType) {
            throw new \Exception();
        }

        parent::__construct(null, $type);
    }
}
