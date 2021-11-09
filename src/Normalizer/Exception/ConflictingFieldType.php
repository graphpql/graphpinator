<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Exception;

final class ConflictingFieldType extends NormalizerError
{
    public const MESSAGE = 'Fields are not compatible for merging: types do not match.';
}
