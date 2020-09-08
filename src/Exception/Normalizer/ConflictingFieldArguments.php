<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Normalizer;

final class ConflictingFieldArguments extends \Graphpinator\Exception\Normalizer\NormalizerError
{
    public const MESSAGE = 'Arguments cannot be merged.';
}
