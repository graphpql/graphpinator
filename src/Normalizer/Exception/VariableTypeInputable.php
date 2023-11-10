<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Exception;

final class VariableTypeInputable extends \Graphpinator\Normalizer\Exception\NormalizerError
{
    public const MESSAGE = 'Variable "%s" does not have inputable type.';

    public function __construct(string $name)
    {
        parent::__construct([$name]);
    }
}
