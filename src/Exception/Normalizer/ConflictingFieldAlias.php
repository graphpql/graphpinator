<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Normalizer;

final class ConflictingFieldAlias extends \Graphpinator\Exception\Normalizer\NormalizerError
{
    public const MESSAGE = 'Alias name conflicts with different field name.';
}
