<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

final class LeafValue implements \Graphpinator\Value\InputedValue, \Graphpinator\Value\OutputValue
{
    use \Nette\SmartObject;

    private \Graphpinator\Type\Contract\LeafDefinition $type;
    private string|int|float|bool $rawValue;

    public function __construct(\Graphpinator\Type\Contract\LeafDefinition $type, mixed $rawValue, bool $inputed)
    {
        if (!$type->validateNonNullValue($rawValue)) {
            throw new \Graphpinator\Exception\Value\InvalidValue($type->getName(), $rawValue, $inputed);
        }

        $this->type = $type;
        $this->rawValue = $rawValue;
    }

    public function getRawValue() : string|int|float|bool
    {
        return $this->rawValue;
    }

    public function getType() : \Graphpinator\Type\Contract\LeafDefinition
    {
        return $this->type;
    }

    public function jsonSerialize() : string|int|float|bool
    {
        return $this->rawValue;
    }

    public function printValue() : string
    {
        return \json_encode($this->rawValue, \JSON_THROW_ON_ERROR);
    }

    public function prettyPrint(int $indentLevel) : string
    {
        return $this->printValue();
    }

    public function applyVariables(\Graphpinator\Resolver\VariableValueSet $variables) : void
    {
        // nothing here
    }

    public function isSame(Value $compare) : bool
    {
        return $compare instanceof self
            && $this->rawValue === $compare->getRawValue();
    }
}
