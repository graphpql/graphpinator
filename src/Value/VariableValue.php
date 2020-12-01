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
        if (!$variable->getType()->isInstanceOf($type)) {
            throw new \Exception();
        }

        $this->type = $type;
        $this->variable = $variable;
    }

    public function getRawValue() : \stdClass|array|string|int|float|bool|null
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

    public function applyVariables(\Graphpinator\Resolver\VariableValueSet $variables) : void
    {
        $this->value = $this->variable->createInputedValue($variables);
    }

    public function isSame(Value $compare) : bool
    {
        return $compare instanceof self;
    }
}
