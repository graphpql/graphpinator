<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Exception;

final class UnionTypeMustDefineOneOrMoreTypes extends TypeError
{
    public const MESSAGE = 'A Union type must include one or more member types.';
}
