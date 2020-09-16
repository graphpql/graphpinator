<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Constraint;

final class MaxConstraintNotSatisfied extends \Graphpinator\Exception\Parser\ParserError
{
    public const MESSAGE = 'Max constraint was not satisfied.';
}
