<?php

declare(strict_types = 1);

namespace PGQL\Field;

final class ResolveResult
{
    use \Nette\SmartObject;

    private \PGQL\Type\Contract\Resolvable $type;
    private \PGQL\Value\ValidatedValue $result;

    private function __construct(\PGQL\Type\Contract\Resolvable $type, \PGQL\Value\ValidatedValue $value)
    {
        if (!$type->getNamedType() instanceof \PGQL\Type\Contract\ConcreteDefinition) {
            throw new \Exception('Abstract type fields need to return ResolveResult with concrete resolution.');
        }

        $this->type = $type;
        $this->result = $value;
    }

    public static function fromRaw(\PGQL\Type\Contract\Resolvable $type, $rawValue) : self
    {
        return new self($type, $type->createValue($rawValue));
    }

    public static function fromValidated(\PGQL\Value\ValidatedValue $value) : self
    {
        return new self($value->getType(), $value);
    }

    public function getType() : \PGQL\Type\Contract\Resolvable
    {
        return $this->type;
    }

    public function getResult() : \PGQL\Value\ValidatedValue
    {
        return $this->result;
    }
}
