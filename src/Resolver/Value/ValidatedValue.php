<?php

declare(strict_types = 1);

namespace Graphpinator\Resolver\Value;

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

    public static function create($rawValue, \Graphpinator\Type\Contract\Definition $type) : self
    {
        if ($rawValue === null) {
            return new \Graphpinator\Resolver\Value\NullValue($type);
        }

        if ($type instanceof \Graphpinator\Type\Contract\Inputable && $type->isInputable()) {
            $rawValue = $type->applyDefaults($rawValue);
        }

        return new static($rawValue, $type);
    }

    abstract public function printValue(bool $prettyPrint) : string;

    public function getType() : \Graphpinator\Type\Contract\Definition
    {
        return $this->type;
    }

    //@phpcs:ignore SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingAnyTypeHint
    public function getRawValue()
    {
        return $this->value;
    }

    //@phpcs:ignore SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingAnyTypeHint
    public function jsonSerialize()
    {
        return $this->value;
    }
}
