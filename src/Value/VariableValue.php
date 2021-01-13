<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

final class VariableValue implements \Graphpinator\Value\InputedValue
{
    use \Nette\SmartObject;

    private \Graphpinator\Type\Contract\Inputable $type;
    private \Graphpinator\Normalizer\Variable\Variable $variable;
    private ?\Graphpinator\Value\Value $value = null;

    public function __construct(\Graphpinator\Type\Contract\Inputable $type, \Graphpinator\Normalizer\Variable\Variable $variable)
    {
        // flipped because of contravariance, isInstanceOf is covariant
        if (!$type->isInstanceOf($variable->getType())) {
            throw new \Graphpinator\Exception\Normalizer\VariableTypeMismatch();
        }

        $this->type = $type;
        $this->variable = $variable;
    }

    public function getRawValue() : mixed
    {
        return $this->value->getRawValue();
    }

    public function getType() : \Graphpinator\Type\Contract\Inputable
    {
        return $this->type;
    }

    public function printValue() : string
    {
        throw new \Graphpinator\Exception\OperationNotSupported();
    }

    public function prettyPrint(int $indentLevel) : string
    {
        throw new \Graphpinator\Exception\OperationNotSupported();
    }

    public function applyVariables(\Graphpinator\Normalizer\VariableValueSet $variables) : void
    {
        $this->value = $variables->offsetGet($this->variable->getName());
    }

    public function isSame(Value $compare) : bool
    {
        return $compare instanceof self;
    }
}
