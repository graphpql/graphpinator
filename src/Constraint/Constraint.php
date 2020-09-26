<?php

declare(strict_types = 1);

namespace Graphpinator\Constraint;

interface Constraint
{
    public function validate(\Graphpinator\Resolver\Value\ValidatedValue $value) : void;

    public function validateType(\Graphpinator\Type\Contract\Inputable $type) : bool;

    public function print() : string;
}
