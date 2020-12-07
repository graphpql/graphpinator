<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Normalizer;

final class VariableTypeMismatch extends \Graphpinator\Exception\Normalizer\NormalizerError
{
    public const MESSAGE = 'Variable type does not match its usage.';
}
