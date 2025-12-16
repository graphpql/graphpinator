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

    #[\Override]
    public function getRawValue(bool $forResolvers = false) : ?bool
    {
        return null;
    }

    #[\Override]
    public function getType() : Inputable
    {
        return $this->type;
    }

    #[\Override]
    public function printValue() : string
    {
        return 'null';
    }

    #[\Override]
    public function applyVariables(VariableValueSet $variables) : void
    {
        // nothing here
    }

    #[\Override]
    public function resolveRemainingDirectives() : void
    {
        // nothing here
    }

    #[\Override]
    public function isSame(Value $compare) : bool
    {
        return $compare instanceof self;
    }
}
