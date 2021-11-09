<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Exception;

final class OperationNotSupported extends NormalizerError
{
    public const MESSAGE = 'Operation "%s" is not supported by this service.';

    public function __construct(string $operation)
    {
        $this->messageArgs = [$operation];

        parent::__construct();
    }
}
