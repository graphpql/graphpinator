<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Contract;

use Graphpinator\Typesystem\ListType;
use Graphpinator\Typesystem\NotNullType;

interface TypeVisitor extends NamedTypeVisitor
{
    public function visitNotNull(NotNullType $notNull) : mixed;

    public function visitList(ListType $list) : mixed;
}
