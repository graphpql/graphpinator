<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Parser;

final class DuplicateOperation extends \Graphpinator\Exception\Parser\ParserError
{
    public const MESSAGE = 'Operation with this name already exists in current request.';
}
