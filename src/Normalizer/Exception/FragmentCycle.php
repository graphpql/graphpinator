<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Exception;

final class FragmentCycle extends NormalizerError
{
    public const MESSAGE = 'Fragment cycle detected.';
}
