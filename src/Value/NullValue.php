<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

use Graphpinator\Typesystem\Contract\Type;
use Graphpinator\Value\Contract\InputedValue;
use Graphpinator\Value\Contract\InputedValueVisitor;
use Graphpinator\Value\Contract\OutputValue;

final readonly class NullValue implements InputedValue, OutputValue
{
    public function __construct(
        private Type $type,
    )
    {
    }

    #[\Override]
    public function accept(InputedValueVisitor $visitor) : mixed
    {
        return $visitor->visitNull($this);
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
}
