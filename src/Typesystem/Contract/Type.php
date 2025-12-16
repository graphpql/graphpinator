<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Contract;

interface Type
{
    /**
     * @template T of mixed
     * @param TypeVisitor<T> $visitor
     * @return T
     */
    public function accept(TypeVisitor $visitor) : mixed;
}
