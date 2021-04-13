<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer;

final class VariableValueSet
{
    use \Nette\SmartObject;

    public function __construct(
        private array $variables,
    ) {}

    public function get(string $offset) : \Graphpinator\Value\InputedValue
    {
        return $this->variables[$offset];
    }
}
