<?php

declare(strict_types = 1);

namespace Infinityloop\Graphpinator\Value;

final class NullValue extends \Infinityloop\Graphpinator\Value\ValidatedValue
{
    public function __construct(\Infinityloop\Graphpinator\Type\Contract\Definition $type)
    {
        if ($type instanceof \Infinityloop\Graphpinator\Type\NotNullType) {
            throw new \Exception();
        }

        parent::__construct(null, $type);
    }
}
