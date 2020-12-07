<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Variable;

final class Variable
{
    use \Nette\SmartObject;

    private string $name;
    private \Graphpinator\Type\Contract\Inputable $type;
    private ?\Graphpinator\Value\InputedValue $defaultValue;

    public function __construct(
        string $name,
        \Graphpinator\Type\Contract\Definition $type,
        ?\Graphpinator\Parser\Value\Value $default = null
    )
    {
        if (!$type instanceof \Graphpinator\Type\Contract\Inputable || !$type->isInputable()) {
            throw new \Graphpinator\Exception\Normalizer\VariableTypeInputable();
        }

        $this->name = $name;
        $this->type = $type;
        $this->defaultValue = $default instanceof \Graphpinator\Parser\Value\Value
            ? $type->createInputedValue($default->getRawValue())
            : null;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getType() : \Graphpinator\Type\Contract\Inputable
    {
        return $this->type;
    }

    public function getDefaultValue() : ?\Graphpinator\Value\InputedValue
    {
        return $this->defaultValue;
    }

    public function createInputedValue(\stdClass $variables) : \Graphpinator\Value\InputedValue
    {
        if (isset($variables->{$this->name})) {
            return $this->type->createInputedValue($variables->{$this->name});
        }

        if ($this->defaultValue instanceof \Graphpinator\Value\InputedValue) {
            return $this->defaultValue;
        }

        return $this->type->createInputedValue(null);
    }
}
