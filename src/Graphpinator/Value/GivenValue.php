<?php

declare(strict_types = 1);

namespace Infinityloop\Graphpinator\Value;

final class GivenValue
{
    use \Nette\SmartObject;

    private string $name;
    private $value;

    public function __construct($value, string $name)
    {
        $this->value = $value;
        $this->name = $name;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getValue()
    {
        return $this->value;
    }
}
