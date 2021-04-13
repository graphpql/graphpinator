<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem;

interface TypeVisitor extends \Graphpinator\Typesystem\NamedTypeVisitor
{
    public function visitNotNull(\Graphpinator\Type\NotNullType $notNull) : mixed;

    public function visitList(\Graphpinator\Type\ListType $list) : mixed;
}
