<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Exception;

abstract class NormalizerError extends \Graphpinator\Exception\GraphpinatorBase
{
    protected function isOutputable() : bool
    {
        return true;
    }
}
