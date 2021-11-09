<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Exception;

final class VariableTypeMismatch extends NormalizerError
{
    public const MESSAGE = 'Variable type does not match its usage.';
}
