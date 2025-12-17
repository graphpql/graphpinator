<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

use Graphpinator\Normalizer\VariableValueSet;
use Graphpinator\Typesystem\Contract\Type;

final readonly class NullValue implements InputedValue, OutputValue
{
    public function __construct(
        private Type $type,
    )
    {
    }

    #[\Override]
    public function getRawValue(bool $forResolvers = false) : null
    {
        return null;
    }

    #[\Override]
    public function getType() : Type
    {
        return $this->type;
    }

    #[\Override]
    public function jsonSerialize() : null
    {
        return null;
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
