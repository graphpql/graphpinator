<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem;

use Graphpinator\Typesystem\Contract\ModifierType;
use Graphpinator\Typesystem\Contract\Type;
use Graphpinator\Typesystem\Contract\TypeVisitor;

final class NotNullType extends ModifierType
{
    #[\Override]
    public function accept(TypeVisitor $visitor) : mixed
    {
        return $visitor->visitNotNull($this);
    }
}
