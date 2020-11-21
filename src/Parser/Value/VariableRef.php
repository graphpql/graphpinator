<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\Value;

final class VariableRef implements \Graphpinator\Parser\Value\Value
{
    use \Nette\SmartObject;

    private string $varName;

    public function __construct(string $name)
    {
        $this->varName = $name;
    }

    public function getRawValue() : ?bool
    {
        throw new \Graphpinator\Exception\OperationNotSupported();
    }

    public function getVarName() : string
    {
        return $this->varName;
    }

    public function applyVariables(\Graphpinator\Resolver\VariableValueSet $variables) : \Graphpinator\Parser\Value\Value
    {
        if ($variables->offsetExists($this->varName)) {
            $value = $variables[$this->varName];

            /*if ($value instanceof \Graphpinator\Value\ListValue) {
                return new ListVal($value->getRawValue());
            }

            if ($value instanceof \Graphpinator\Value\InputValue) {
                return new ObjectVal($value->getRawValue());
            }*/

            return new \Graphpinator\Parser\Value\Literal($value->getRawValue());
        }

        throw new \Graphpinator\Exception\Resolver\MissingVariable();
    }

    public function isSame(Value $compare) : bool
    {
        return $compare instanceof self
            && $this->varName === $compare->getVarName();
    }
}
