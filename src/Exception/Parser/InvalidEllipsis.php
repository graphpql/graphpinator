<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Parser;

final class InvalidEllipsis extends ParseError
{
    public const MESSAGE = 'Invalid ellipsis - three dots are expected for form ellipsis.';
}
