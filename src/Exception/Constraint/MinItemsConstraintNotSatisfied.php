<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Constraint;

final class MinItemsConstraintNotSatisfied extends \Graphpinator\Exception\Parser\ParserError
{
    public const MESSAGE = 'Min items constraint was not satisfied.';
}
