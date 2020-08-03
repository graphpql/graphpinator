<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Normalizer;

final class FieldNotDefined extends \Graphpinator\Exception\Normalizer\NormalizerError
{
    public const MESSAGE = 'Field is not defined.';
}
