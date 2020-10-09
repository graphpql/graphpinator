<?php

declare(strict_types = 1);

namespace Graphpinator\Constraint;

interface Constraint
{
    public function validate(\Graphpinator\Value\Value $value) : void;

    public function validateType(\Graphpinator\Type\Contract\Definition $type) : bool;

    public function print() : string;
}
