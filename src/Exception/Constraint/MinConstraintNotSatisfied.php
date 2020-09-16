<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Constraint;

final class MinConstraintNotSatisfied extends \Graphpinator\Exception\Parser\ParserError
{
    public const MESSAGE = 'Min constraint was not satisfied.';
}
