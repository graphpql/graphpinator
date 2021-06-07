<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

final class NullInputedValue implements \Graphpinator\Value\InputedValue, \Graphpinator\Value\NullValue
{
    use \Nette\SmartObject;

    public function __construct(
        private \Graphpinator\Typesystem\Contract\Inputable $type,
    )
    {
    }

    public function getRawValue(bool $forResolvers = false) : ?bool
    {
        return null;
    }

    public function getType() : \Graphpinator\Typesystem\Contract\Inputable
    {
        return $this->type;
    }

    public function printValue() : string
    {
        return 'null';
    }

    public function applyVariables(\Graphpinator\Normalizer\VariableValueSet $variables) : void
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
