<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Parser;

final class EmptyRequest extends ParserError
{
    public const MESSAGE = 'Request is empty.';
}
