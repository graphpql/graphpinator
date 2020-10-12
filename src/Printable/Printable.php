<?php

declare(strict_types = 1);

namespace Graphpinator\Printable;

interface Printable
{
    public function printSchema(int $indentLevel) : string;

    public function hasDescription() : bool;
}
