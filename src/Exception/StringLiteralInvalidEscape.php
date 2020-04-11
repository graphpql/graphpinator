<?php

declare(strict_types = 1);

namespace Graphpinator\Exception;

final class StringLiteralInvalidEscape extends \Exception
{
    public function __construct(int $position)
    {
        parent::__construct('String literal with invalid escape sequence.', $position);
    }
}
