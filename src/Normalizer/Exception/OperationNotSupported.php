<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Exception;

use Graphpinator\Parser\OperationType;

final class OperationNotSupported extends NormalizerError
{
    public const MESSAGE = 'Operation "%s" is not supported by this service.';

    public function __construct(
        OperationType $operation,
    )
    {
        parent::__construct([$operation->value]);
    }
}
