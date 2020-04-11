<?php

declare(strict_types = 1);

namespace Graphpinator\Exception;

abstract class Tokenizer extends \Exception
{
    protected int $position;

    public function __construct(string $message, int $position)
    {
        parent::__construct($message, 0, null);

        $this->position = $position;
    }
}
