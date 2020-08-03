<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Normalizer;

final class FragmentSpreadNotDefined extends \Graphpinator\Exception\Normalizer\NormalizerError
{
    public const MESSAGE = 'FragmentSpread is not defined.';
}
