<?php

declare(strict_types = 1);

namespace Graphpinator\Exception;

final class StringLiteralNewLine extends \Exception
{
    public function __construct(int $position)
    {
        parent::__construct('Simple string literal cannot span across multiple lines. Use block literal or escape sequence.', $position);
    }
}
