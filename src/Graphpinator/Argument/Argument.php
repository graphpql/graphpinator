<?php

declare(strict_types = 1);

namespace Infinityloop\Graphpinator\Argument;

final class Argument
{
    use \Nette\SmartObject;

    private string $name;
    private \Infinityloop\Graphpinator\Type\Contract\Inputable $type;
    private ?\Infinityloop\Graphpinator\Value\ValidatedValue $defaultValue;

    public function __construct(string $name, \Infinityloop\Graphpinator\Type\Contract\Inputable $type, $defaultValue = null)
    {
        $this->name = $name;
        $this->type = $type;

        if (\func_num_args() === 3) {
            $defaultValue = $type->createValue($defaultValue);
        }

        $this->defaultValue = $defaultValue;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getType() : \Infinityloop\Graphpinator\Type\Contract\Inputable
    {
        return $this->type;
    }

    public function getDefaultValue() : ?\Infinityloop\Graphpinator\Value\ValidatedValue
    {
        return $this->defaultValue;
    }
}
