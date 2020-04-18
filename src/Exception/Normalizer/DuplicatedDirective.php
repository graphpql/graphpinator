<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Normalizer;

final class DuplicatedDirective extends NormalizerError
{
    public const MESSAGE = 'Duplicated directive which is not repeatable.';
}
