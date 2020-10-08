<?php

declare(strict_types = 1);

namespace Graphpinator\Resolver\Value;

final class NullValue extends \Graphpinator\Resolver\Value\ValidatedValue
{
    public function __construct(\Graphpinator\Type\Contract\Definition $type)
    {
        if ($type instanceof \Graphpinator\Type\NotNullType) {
            throw new \Graphpinator\Exception\Value\ValueCannotBeNull();
        }

        parent::__construct(null, $type);
    }

    public function printValue(bool $prettyPrint = false, int $indentLevel = 0) : string
    {
        return 'null';
    }
}
