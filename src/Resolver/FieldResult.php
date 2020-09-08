<?php

declare(strict_types = 1);

namespace Graphpinator\Resolver;

final class FieldResult
{
    use \Nette\SmartObject;

    private \Graphpinator\Type\Contract\Definition $type;
    private \Graphpinator\Resolver\Value\ValidatedValue $result;

    private function __construct(\Graphpinator\Type\Contract\Definition $type, \Graphpinator\Resolver\Value\ValidatedValue $value)
    {
        $this->type = $type;
        $this->result = $value;
    }

    public static function fromRaw(\Graphpinator\Type\Contract\Definition $type, $rawValue) : self
    {
        return new self($type, $type->createValue($rawValue));
    }

    public static function fromValidated(\Graphpinator\Resolver\Value\ValidatedValue $value) : self
    {
        if ($value->getType()->getNamedType() instanceof \Graphpinator\Type\Contract\ConcreteDefinition) {
            return new self($value->getType(), $value);
        }

        throw new \Graphpinator\Exception\Resolver\FieldResultAbstract();
    }

    public function getType() : \Graphpinator\Type\Contract\Definition
    {
        return $this->type;
    }

    public function getResult() : \Graphpinator\Resolver\Value\ValidatedValue
    {
        return $this->result;
    }
}
