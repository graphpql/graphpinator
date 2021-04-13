<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Exception;

final class VariableInConstContext extends \Graphpinator\Normalizer\Exception\NormalizerError
{
    public const MESSAGE = 'Variable cannot be used in constant context (variable default value).';
}
