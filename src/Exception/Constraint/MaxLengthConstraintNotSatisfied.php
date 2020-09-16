<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Constraint;

final class MaxLengthConstraintNotSatisfied extends \Graphpinator\Exception\Parser\ParserError
{
    public const MESSAGE = 'Max length constraint was not satisfied.';
}
