<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Variable;

final class Variable
{
    use \Nette\SmartObject;

    private string $name;
    private \Graphpinator\Type\Contract\Inputable $type;
    private ?\Graphpinator\Value\ValidatedValue $default;

    public function __construct(
        string $name,
        \Graphpinator\Type\Contract\Inputable $type,
        ?\Graphpinator\Parser\Value\Value $default = null
    ) {
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

    public function getDefault() : ?\Graphpinator\Value\ValidatedValue
    {
        return $this->default;
    }

    public function createValue(\Infinityloop\Utils\Json $variables) : \Graphpinator\Value\ValidatedValue
    {
        $value = null;

        if (isset($variables[$this->name])) {
            $value = $variables[$this->name];
        } elseif ($this->default instanceof \Graphpinator\Value\ValidatedValue) {
            return $this->default;
        }

        return $this->type->createValue($value);
    }
}
