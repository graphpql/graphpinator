<?php

declare(strict_types = 1);

namespace PGQL\Value;

class ValidatedValue extends \PGQL\Value\Value implements \JsonSerializable
{
    protected \PGQL\Type\Definition $type;

    public function __construct($value, \PGQL\Type\Definition $type)
    {
        if ($type instanceof \PGQL\Type\Inputable) {
            $value = $type->applyDefaults($value);
        }

        $type->validateValue($value);

        parent::__construct($value);
        $this->type = $type;
    }

    public function getType() : \PGQL\Type\Definition
    {
        return $this->type;
    }

    public function jsonSerialize()
    {
        return $this->value;
    }
}
