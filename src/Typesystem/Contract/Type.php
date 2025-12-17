<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Contract;

interface Type
{
    /**
     * @phpcs:ignore
     * @template T of mixed
     * @param TypeVisitor<T> $visitor
     * @return T
     */
    public function accept(TypeVisitor $visitor) : mixed;
}
