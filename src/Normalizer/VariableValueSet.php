<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer;

use Graphpinator\Value\InputedValue;

final class VariableValueSet
{
    public function __construct(
        private array $variables,
    )
    {
    }

    public function get(string $offset) : InputedValue
    {
        return $this->variables[$offset];
    }
}
