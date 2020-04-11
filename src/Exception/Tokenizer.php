<?php

declare(strict_types = 1);

namespace Graphpinator\Exception;

abstract class Tokenizer extends \Exception
{
    public const MESSAGE = '';

    protected int $position;

    public function __construct(int $position)
    {
        parent::__construct(static::MESSAGE);

        $this->position = $position;
    }
}
