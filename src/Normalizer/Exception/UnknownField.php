<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Exception;

final class UnknownField extends NormalizerError
{
    public const MESSAGE = 'Unknown field "%s" requested for type "%s".';

    public function __construct(
        string $field,
        string $type,
    )
    {
        parent::__construct([$field, $type]);
    }
}
