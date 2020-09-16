<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Constraint;

final class NegativeLengthParameter extends \Graphpinator\Exception\Parser\ParserError
{
    public const MESSAGE = 'Invalid length argument passed to minLength/maxLength constraint.';

    protected function isOutputable() : bool
    {
        return false;
    }
}
