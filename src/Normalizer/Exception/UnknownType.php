<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Exception;

final class UnknownType extends NormalizerError
{
    public const MESSAGE = 'Unknown type "%s".';

    public function __construct(
        string $type,
    )
    {
        parent::__construct([$type]);
    }
}
