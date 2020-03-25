<?php

declare(strict_types = 1);

namespace PGQL\Argument;

final class Argument
{
    use \Nette\SmartObject;

    private string $name;
    private \PGQL\Type\Inputable $type;
    private ?\PGQL\Value\ValidatedValue $defaultValue;

    public function __construct(string $name, \PGQL\Type\Inputable $type, $defaultValue = null)
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

    public function getType() : \PGQL\Type\Inputable
    {
        return $this->type;
    }

    public function getDefaultValue() : ?\PGQL\Value\ValidatedValue
    {
        return $this->defaultValue;
    }
}
