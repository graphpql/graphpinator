<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Exception;

final class UnknownVariable extends NormalizerError
{
    public const MESSAGE = 'Unknown variable "%s".';

    public function __construct(
        string $varName,
    )
    {
        parent::__construct([$varName]);
    }
}
