<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Exception;

final class OneOfDirectiveNotSatisfied extends TypeError
{
    public const MESSAGE = 'Exactly one field must be specified and be not null.';

    public function isOutputable() : bool
    {
        return true;
    }
}
