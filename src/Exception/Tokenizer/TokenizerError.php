<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Tokenizer;

abstract class TokenizerError extends \Graphpinator\Exception\GraphpinatorBase
{
    public function isOutputable() : bool
    {
        return true;
    }
}
