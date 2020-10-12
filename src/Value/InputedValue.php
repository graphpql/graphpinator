<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

interface InputedValue extends \Graphpinator\Value\Value
{
    /**
     * Function used to print value in introspection.
     */
    public function printValue() : string;

    /**
     * Function used to print value in more readable form (used in schema printing).
     */
    public function prettyPrint(int $indentLevel) : string;

    public function getType() : \Graphpinator\Type\Contract\Inputable;
}
