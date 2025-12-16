<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Exception;

use Graphpinator\Exception\GraphpinatorBase;

abstract class NormalizerError extends GraphpinatorBase
{
    #[\Override]
    public function isOutputable() : bool
    {
        return true;
    }
}
