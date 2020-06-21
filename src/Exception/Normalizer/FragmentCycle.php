<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Normalizer;

final class FragmentCycle extends \Graphpinator\Exception\Normalizer\NormalizerError
{
    public const MESSAGE = 'Fragment cycle detected.';
}
