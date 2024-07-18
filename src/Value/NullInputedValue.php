<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

use Graphpinator\Normalizer\VariableValueSet;
use Graphpinator\Typesystem\Contract\Inputable;

final class NullInputedValue implements InputedValue, NullValue
{
    public function __construct(
        private Inputable $type,
    )
    {
    }

    public function getRawValue(bool $forResolvers = false) : ?bool
    {
        return null;
    }

    public function getType() : Inputable
    {
        return $this->type;
    }

    public function printValue() : string
    {
        return 'null';
    }

    public function applyVariables(VariableValueSet $variables) : void
    {
        // nothing here
    }

    public function resolveRemainingDirectives() : void
    {
        // nothing here
    }

    public function isSame(Value $compare) : bool
    {
        return $compare instanceof self;
    }
}
