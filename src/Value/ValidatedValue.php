<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

abstract class ValidatedValue implements \JsonSerializable
{
    use \Nette\SmartObject;

    protected \Graphpinator\Type\Contract\Definition $type;
    protected $value;

    protected function __construct($value, \Graphpinator\Type\Contract\Definition $type)
    {
        $this->value = $value;
        $this->type = $type;
    }

    public static function create($rawValue, \Graphpinator\Type\Contract\Definition $type)
    {
        if ($rawValue === null) {
            return new \Graphpinator\Value\NullValue($type);
        }

        if ($type->isInputable()) {
            $rawValue = $type->applyDefaults($rawValue);
        }

        return new static($rawValue, $type);
    }

    public function getType() : \Graphpinator\Type\Contract\Definition
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
