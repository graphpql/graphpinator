<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Exception;

final class ConflictingFieldDirectives extends NormalizerError
{
    public const MESSAGE = 'Directives cannot be merged.';
}
