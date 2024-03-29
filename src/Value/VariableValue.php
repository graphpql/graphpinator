<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

final class VariableValue implements \Graphpinator\Value\InputedValue
{
    private ?\Graphpinator\Value\InputedValue $value = null;

    public function __construct(
        private \Graphpinator\Typesystem\Contract\Inputable $type,
        private \Graphpinator\Normalizer\Variable\Variable $variable,
    )
    {
        if (!$variable->getType()->isInstanceOf($type)) {
            throw new \Graphpinator\Normalizer\Exception\VariableTypeMismatch();
        }
    }

    public function getRawValue(bool $forResolvers = false) : mixed
    {
        return $this->value->getRawValue($forResolvers);
    }

    public function getConcreteValue() : \Graphpinator\Value\InputedValue
    {
        return $this->value;
    }

    public function getVariable() : \Graphpinator\Normalizer\Variable\Variable
    {
        return $this->variable;
    }

    public function getType() : \Graphpinator\Typesystem\Contract\Inputable
    {
        return $this->type;
    }

    public function printValue() : string
    {
        throw new \Graphpinator\Exception\OperationNotSupported();
    }

    public function applyVariables(\Graphpinator\Normalizer\VariableValueSet $variables) : void
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
