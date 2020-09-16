<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Constraint;

final class InvalidOneOfParameter extends \Graphpinator\Exception\Parser\ParserError
{
    public const MESSAGE = 'Invalid array passed as oneOf constraint.';

    protected function isOutputable() : bool
    {
        return false;
    }
}
