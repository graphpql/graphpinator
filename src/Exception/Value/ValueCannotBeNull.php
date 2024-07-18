<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Value;

final class ValueCannotBeNull extends ValueError
{
    public const MESSAGE = 'Not-null type with null value.';
}
