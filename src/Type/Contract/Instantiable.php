<?php

declare(strict_types = 1);

namespace PGQL\Type\Contract;

interface Instantiable extends \PGQL\Type\Contract\Definition
{
    public function createValue($rawValue) : \PGQL\Value\ValidatedValue;

    public function validateValue($rawValue) : void;
}
