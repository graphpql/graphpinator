<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Value;

abstract class ValueError extends \Graphpinator\Exception\GraphpinatorBase
{
    public function isOutputable(): bool
    {
        return true;
    }
}
