<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Normalizer;

final class VariableTypeInputable extends \Graphpinator\Exception\Normalizer\NormalizerError
{
    public const MESSAGE = 'Variable "%s" does not have inputable type.';

    public function __construct(string $name)
    {
        $this->messageArgs = [$name];

        parent::__construct();
    }
}
