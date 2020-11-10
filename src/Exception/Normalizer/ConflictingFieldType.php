<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Normalizer;

final class ConflictingFieldType extends \Graphpinator\Exception\Normalizer\NormalizerError
{
    public const MESSAGE = 'Fields are not compatible for merging: types do not match.';
}
