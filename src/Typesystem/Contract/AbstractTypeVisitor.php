<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Contract;

use Graphpinator\Typesystem\InterfaceType;
use Graphpinator\Typesystem\UnionType;

/**
 * @phpcs:ignore
 * @template T of mixed
 */
interface AbstractTypeVisitor
{
    /**
     * @return T
     */
    public function visitInterface(InterfaceType $interface) : mixed;

    /**
     * @return T
     */
    public function visitUnion(UnionType $union) : mixed;
}
