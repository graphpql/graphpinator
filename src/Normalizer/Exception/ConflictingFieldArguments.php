<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Exception;

final class ConflictingFieldArguments extends \Graphpinator\Normalizer\Exception\NormalizerError
{
    public const MESSAGE = 'Arguments cannot be merged.';
}
