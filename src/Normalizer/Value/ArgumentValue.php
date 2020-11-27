<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Value;

final class ArgumentValue
{
    use \Nette\SmartObject;

    private \Graphpinator\Value\InputedValue $value;
    private string $name;

    public function __construct(\Graphpinator\Type\Contract\Definition $value, string $name)
    {
        $this->value = $value;
        $this->name = $name;
    }

    public function getValue() : \Graphpinator\Value\InputedValue
    {
        return $this->value;
    }

    public function getRawValue() : \stdClass|array|string|int|float|bool|null
    {
        return $this->value->getRawValue();
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function applyVariables(\Graphpinator\Resolver\VariableValueSet $variables) : void
    {
        $this->value->applyVariables($variables);
    }
}
