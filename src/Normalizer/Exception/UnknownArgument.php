<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Exception;

final class UnknownArgument extends NormalizerError
{
    public const MESSAGE = 'Unknown argument "%s" provided.';

    public function __construct(
        string $argument,
    )
    {
        parent::__construct([$argument]);
    }
}
