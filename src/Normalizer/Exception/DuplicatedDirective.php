<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Exception;

final class DuplicatedDirective extends NormalizerError
{
    public const MESSAGE = 'Duplicated directive "%s" which is not repeatable.';

    public function __construct(string $name)
    {
        parent::__construct([$name]);
    }
}
