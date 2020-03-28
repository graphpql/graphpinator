<?php

declare(strict_types = 1);

namespace PGQL\Value;

abstract class ValidatedValue implements \JsonSerializable
{
    use \Nette\SmartObject;

    protected \PGQL\Type\Contract\Definition $type;
    protected $value;

    protected function __construct($value, \PGQL\Type\Contract\Definition $type)
    {
        $this->value = $value;
        $this->type = $type;
    }

    public static function create($rawValue, \PGQL\Type\Contract\Definition $type)
    {
        if ($rawValue === null) {
            return new \PGQL\Value\NullValue($type);
        }

        if ($type instanceof \PGQL\Type\Contract\Inputable) {
            $rawValue = $type->applyDefaults($rawValue);
        }

        return new static($rawValue, $type);
    }

    public function getType() : \PGQL\Type\Contract\Definition
    {
        return $this->type;
    }

    public function getRawValue()
    {
        return $this->value;
    }

    public function jsonSerialize()
    {
        return $this->value;
    }
}
