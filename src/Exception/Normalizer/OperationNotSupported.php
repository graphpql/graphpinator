<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Normalizer;

final class OperationNotSupported extends NormalizerError
{
    public const MESSAGE = 'Operation is not supported by service.';
}
