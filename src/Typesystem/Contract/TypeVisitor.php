<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Contract;

use Graphpinator\Typesystem\ListType;
use Graphpinator\Typesystem\NotNullType;

/**
 * @template T
 * @template-extends NamedTypeVisitor<T>
 */
interface TypeVisitor extends NamedTypeVisitor
{
    /**
     * @return T
     */
    public function visitNotNull(NotNullType $notNull) : mixed;

    /**
     * @return T
     */
    public function visitList(ListType $list) : mixed;
}
