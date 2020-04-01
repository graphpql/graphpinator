<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

final class NullValue extends \Graphpinator\Value\ValidatedValue
{
    public function __construct(\Graphpinator\Type\Contract\Definition $type)
    {
        if ($type instanceof \Graphpinator\Type\NotNullType) {
            throw new \Exception();
        }

        parent::__construct(null, $type);
    }
}
