<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

final class VariableValue implements \Graphpinator\Value\InputedValue
{
    use \Nette\SmartObject;

    private ?\Graphpinator\Value\InputedValue $value = null;

    public function __construct(private \Graphpinator\Typesystem\Contract\Inputable $type, private \Graphpinator\Normalizer\Variable\Variable $variable)
    {
        // flipped because of contravariance, isInstanceOf is covariant
        if (!$type->isInstanceOf($variable->getType())) {
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
