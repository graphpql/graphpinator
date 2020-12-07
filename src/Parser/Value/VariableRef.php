<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\Value;

final class VariableRef implements \Graphpinator\Parser\Value\Value
{
    use \Nette\SmartObject;

    public function __construct(
        private string $varName,
    ) {}

    public function getRawValue() : ?bool
    {
        throw new \Graphpinator\Exception\OperationNotSupported();
    }

    public function createInputedValue(
        \Graphpinator\Type\Contract\Inputable $type,
        \Graphpinator\Normalizer\Variable\VariableSet $variableSet,
    ) : \Graphpinator\Value\InputedValue
    {
        if (!$variableSet->offsetExists($this->varName)) {
            throw new \Graphpinator\Exception\Normalizer\UnknownVariable($this->varName);
        }

        return new \Graphpinator\Value\VariableValue($type, $variableSet->offsetGet($this->varName));
    }

    public function getVarName() : string
    {
        return $this->varName;
    }
}
