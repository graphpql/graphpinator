<?php

declare(strict_types = 1);

namespace Graphpinator\Exception;

final class StringLiteralWithoutEnd extends Tokenizer
{
    public function __construct(int $position)
    {
        parent::__construct('String literal without proper end.', $position);
    }
}
