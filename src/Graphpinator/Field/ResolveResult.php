<?php

declare(strict_types = 1);

namespace Infinityloop\Graphpinator\Field;

final class ResolveResult
{
    use \Nette\SmartObject;

    private \Infinityloop\Graphpinator\Type\Contract\Resolvable $type;
    private \Infinityloop\Graphpinator\Value\ValidatedValue $result;

    private function __construct(\Infinityloop\Graphpinator\Type\Contract\Resolvable $type, \Infinityloop\Graphpinator\Value\ValidatedValue $value)
    {
        if (!$type->getNamedType() instanceof \Infinityloop\Graphpinator\Type\Contract\ConcreteDefinition) {
            throw new \Exception('Abstract type fields need to return ResolveResult with concrete resolution.');
        }

        $this->type = $type;
        $this->result = $value;
    }

    public static function fromRaw(\Infinityloop\Graphpinator\Type\Contract\Resolvable $type, $rawValue) : self
    {
        return new self($type, $type->createValue($rawValue));
    }

    public static function fromValidated(\Infinityloop\Graphpinator\Value\ValidatedValue $value) : self
    {
        return new self($value->getType(), $value);
    }

    public function getType() : \Infinityloop\Graphpinator\Type\Contract\Resolvable
    {
        return $this->type;
    }

    public function getResult() : \Infinityloop\Graphpinator\Value\ValidatedValue
    {
        return $this->result;
    }
}
