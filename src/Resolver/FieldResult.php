<?php

declare(strict_types = 1);

namespace Graphpinator\Resolver;

final class FieldResult
{
    use \Nette\SmartObject;

    private \Graphpinator\Type\Contract\Resolvable $type;
    private \Graphpinator\Value\ValidatedValue $result;

    private function __construct(\Graphpinator\Type\Contract\Resolvable $type, \Graphpinator\Value\ValidatedValue $value)
    {
        $this->type = $type;
        $this->result = $value;
    }

    public static function fromRaw(\Graphpinator\Type\Contract\Resolvable $type, $rawValue) : self
    {
        return new self($type, $type->createValue($rawValue));
    }

    public static function fromValidated(\Graphpinator\Value\ValidatedValue $value) : self
    {
        if ($value->getType()->getNamedType() instanceof \Graphpinator\Type\Contract\ConcreteDefinition) {
            return new self($value->getType(), $value);
        }

        throw new \Graphpinator\Exception\Resolver\FieldResultAbstract();
    }

    public function getType() : \Graphpinator\Type\Contract\Resolvable
    {
        return $this->type;
    }

    public function getResult() : \Graphpinator\Value\ValidatedValue
    {
        return $this->result;
    }
}
