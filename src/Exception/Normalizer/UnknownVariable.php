<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Normalizer;

final class UnknownVariable extends \Graphpinator\Exception\Normalizer\NormalizerError
{
    public const MESSAGE = 'Unknown variable "%s".';

    public function __construct(string $varName)
    {
        $this->messageArgs = [$varName];

        parent::__construct();
    }
}
