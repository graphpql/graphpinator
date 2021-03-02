<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Variable;

final class Variable
{
    use \Nette\SmartObject;

    public function __construct(
        private string $name,
        private \Graphpinator\Type\Contract\Inputable $type,
        private ?\Graphpinator\Value\InputedValue $defaultValue,
    ) {}

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
