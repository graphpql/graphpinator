<?php

declare(strict_types = 1);

namespace Graphpinator\Value\Contract;

interface InputedValue extends Value
{
    /**
     * @template T
     * @param InputedValueVisitor<T> $visitor
     * @return T
     */
    public function accept(InputedValueVisitor $visitor) : mixed;
}
