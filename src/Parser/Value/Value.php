<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\Value;

interface Value
{
    public function getRawValue() : \stdClass|array|string|int|float|bool|null;

    public function hasVariables() : bool;

    public function createInputedValue(
        \Graphpinator\Type\Contract\Inputable $type,
        \Graphpinator\Normalizer\Variable\VariableSet $variableSet,
    ) : \Graphpinator\Value\InputedValue;
}
