<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\Value;

final class NamedValue
{
    use \Nette\SmartObject;

    private \Graphpinator\Parser\Value\Value $value;
    private string $name;

    public function __construct(\Graphpinator\Parser\Value\Value $value, string $name)
    {
        $this->value = $value;
        $this->name = $name;
    }

    public function getValue() : \Graphpinator\Parser\Value\Value
    {
        return $this->value;
    }

    public function validate(\Graphpinator\Type\Contract\Inputable $type) : \Graphpinator\Value\ValidatedValue
    {
        return $type->createValue($this->value->getRawValue());
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function normalize(\Graphpinator\Value\ValidatedValueSet $variables) : self
    {
        return new self($this->value->normalize($variables), $this->name);
    }
}
