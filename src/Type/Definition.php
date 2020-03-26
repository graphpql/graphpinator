<?php

declare(strict_types = 1);

namespace PGQL\Type;

interface Definition
{
    public function createValue($rawValue) : \PGQL\Value\ValidatedValue;

    public function validateValue($rawValue) : void;

    public function getNamedType() : NamedDefinition;

    public function isInstanceOf(Definition $type) : bool;
}
