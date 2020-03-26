<?php

declare(strict_types = 1);

namespace PGQL\Value;

class Value
{
    use \Nette\SmartObject;

    protected $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function validate(\PGQL\Type\Definition $type) : ValidatedValue
    {
        return new ValidatedValue($this->value, $type);
    }
}
