<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Constraint;

final class NegativeCountParameter extends \Graphpinator\Exception\Parser\ParserError
{
    public const MESSAGE = 'Invalid count argument passed to minItems/maxitems constraint.';

    protected function isOutputable() : bool
    {
        return false;
    }
}
