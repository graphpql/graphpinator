<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Exception;

final class UnknownDirective extends \Graphpinator\Normalizer\Exception\NormalizerError
{
    public const MESSAGE = 'Unknown directive "%s".';

    public function __construct(string $name)
    {
        $this->messageArgs = [$name];

        parent::__construct();
    }
}
