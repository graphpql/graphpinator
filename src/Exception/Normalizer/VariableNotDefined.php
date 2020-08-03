<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Normalizer;

final class VariableNotDefined extends \Graphpinator\Exception\Normalizer\NormalizerError
{
    public const MESSAGE = 'Variable is not defined.';
}
