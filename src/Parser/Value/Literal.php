<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\Value;

final class Literal implements Value
{
    use \Nette\SmartObject;

    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function normalize(\Graphpinator\Value\ValidatedValueSet $variables) : self
    {
        return new self($this->value);
    }

    public function getRawValue()
    {
        return $this->value;
    }
}
