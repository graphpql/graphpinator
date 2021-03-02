<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Normalizer;

final class OperationNotSupported extends \Graphpinator\Exception\Normalizer\NormalizerError
{
    public const MESSAGE = 'Operation "%s" is not supported by this service.';

    public function __construct(string $operation)
    {
        $this->messageArgs = [$operation];

        parent::__construct();
    }
}
