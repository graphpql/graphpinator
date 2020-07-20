<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Variable;

final class Variable
{
    use \Nette\SmartObject;

    private string $name;
    private \Graphpinator\Type\Contract\Inputable $type;
    private ?\Graphpinator\Resolver\Value\ValidatedValue $default;

    public function __construct(
        string $name,
        \Graphpinator\Type\Contract\Inputable $type,
        ?\Graphpinator\Parser\Value\Value $default = null
    )
    {
        $this->name = $name;
        $this->type = $type;
        $this->default = $default instanceof \Graphpinator\Parser\Value\Value
            ? $type->createValue($default->getRawValue())
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

    public function getDefault() : ?\Graphpinator\Resolver\Value\ValidatedValue
    {
        return $this->default;
    }

    public function createValue(array $variables) : \Graphpinator\Resolver\Value\ValidatedValue
    {
        $value = null;

        if (isset($variables[$this->name])) {
            $value = $variables[$this->name];
        } elseif ($this->default instanceof \Graphpinator\Resolver\Value\ValidatedValue) {
            return $this->default;
        }

        return $this->type->createValue($value);
    }
}
