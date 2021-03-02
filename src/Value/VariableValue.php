<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

final class VariableValue implements \Graphpinator\Value\InputedValue
{
    use \Nette\SmartObject;

    private \Graphpinator\Type\Contract\Inputable $type;
    private \Graphpinator\Normalizer\Variable\Variable $variable;
    private ?\Graphpinator\Value\InputedValue $value = null;

    public function __construct(\Graphpinator\Type\Contract\Inputable $type, \Graphpinator\Normalizer\Variable\Variable $variable)
    {
        // flipped because of contravariance, isInstanceOf is covariant
        if (!$type->isInstanceOf($variable->getType())) {
            throw new \Graphpinator\Exception\Normalizer\VariableTypeMismatch();
        }

        $this->type = $type;
        $this->variable = $variable;
    }

    public function getRawValue(bool $forResolvers = false) : mixed
    {
        return $this->value->getRawValue($forResolvers);
    }
    
    public function getConcreteValue() : \Graphpinator\Value\InputedValue
    {
        return $this->value;
    }

    public function getType() : \Graphpinator\Type\Contract\Inputable
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

    public function isSame(Value $compare) : bool
    {
        return $compare instanceof self
            && $compare->variable->getName() === $this->variable->getName();
    }
}
