<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

use Graphpinator\Exception\OperationNotSupported;
use Graphpinator\Normalizer\Exception\VariableTypeMismatch;
use Graphpinator\Normalizer\Variable\Variable;
use Graphpinator\Normalizer\VariableValueSet;
use Graphpinator\Typesystem\Contract\Inputable;

final class VariableValue implements InputedValue
{
    private ?InputedValue $value = null;

    public function __construct(
        private Inputable $type,
        private Variable $variable,
    )
    {
        if (!$variable->getType()->isInstanceOf($type)) {
            throw new VariableTypeMismatch();
        }
    }

    public function getRawValue(bool $forResolvers = false) : mixed
    {
        return $this->value->getRawValue($forResolvers);
    }

    public function getConcreteValue() : InputedValue
    {
        return $this->value;
    }

    public function getVariable() : Variable
    {
        return $this->variable;
    }

    public function getType() : Inputable
    {
        return $this->type;
    }

    public function printValue() : string
    {
        throw new OperationNotSupported();
    }

    public function applyVariables(VariableValueSet $variables) : void
    {
        $this->value = $variables->get($this->variable->getName());
    }

    public function resolveRemainingDirectives() : void
    {
        $this->value->resolveRemainingDirectives();
    }

    public function isSame(Value $compare) : bool
    {
        return $compare instanceof self
            && $compare->variable->getName() === $this->variable->getName();
    }
}
