<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Normalizer;

final class OperationNotDefined extends \Graphpinator\Exception\Normalizer\NormalizerError
{
    public const MESSAGE = 'Operation is not defined.';
}
