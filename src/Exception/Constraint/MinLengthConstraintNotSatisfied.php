<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Constraint;

final class MinLengthConstraintNotSatisfied extends \Graphpinator\Exception\Parser\ParserError
{
    public const MESSAGE = 'Min length constraint was not satisfied.';
}
