<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Parser;

final class InvalidState extends \Graphpinator\Exception\Parser\ParserError
{
    public const MESSAGE = 'Invalid state.';
}
