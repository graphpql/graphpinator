<?php

declare(strict_types = 1);

namespace PGQL\Value;

final class GivenValue extends \PGQL\Value\Value
{
    private string $name;

    public function __construct($value, string $name)
    {
        parent::__construct($value);
        $this->name = $name;
    }

    public function getName() : string
    {
        return $this->name;
    }
}
