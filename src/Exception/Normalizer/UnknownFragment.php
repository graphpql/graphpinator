<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Normalizer;

final class UnknownFragment extends NormalizerError
{
    public const MESSAGE = 'Fragment is not defined in request.';
}
