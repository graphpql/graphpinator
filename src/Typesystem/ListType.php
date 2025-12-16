<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem;

use Graphpinator\Typesystem\Contract\ModifierType;
use Graphpinator\Typesystem\Contract\TypeVisitor;

final class ListType extends ModifierType
{
    public function notNull() : NotNullType
    {
        return new NotNullType($this);
    }

    #[\Override]
    public function accept(TypeVisitor $visitor) : mixed
    {
        return $visitor->visitList($this);
    }
}
