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
        \Graphpinator\Type\Contract\Inputable $type,
        ?\Graphpinator\Parser\Value\Value $default = null
    )
    {
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
        $value = null;

        if (isset($variables->{$this->name})) {
            $value = $variables->{$this->name};
        } elseif ($this->defaultValue instanceof \Graphpinator\Value\InputedValue) {
            return $this->defaultValue;
        }

        return $this->type->createInputedValue($value);
    }
}
