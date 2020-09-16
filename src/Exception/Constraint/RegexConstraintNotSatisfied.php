<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Constraint;

final class RegexConstraintNotSatisfied extends \Graphpinator\Exception\Parser\ParserError
{
    public const MESSAGE = 'Regex constraint was not satisfied.';
}
