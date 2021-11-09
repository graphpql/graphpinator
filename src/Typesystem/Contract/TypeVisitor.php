<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Contract;

interface TypeVisitor extends NamedTypeVisitor
{
    public function visitNotNull(\Graphpinator\Typesystem\NotNullType $notNull) : mixed;

    public function visitList(\Graphpinator\Typesystem\ListType $list) : mixed;
}
