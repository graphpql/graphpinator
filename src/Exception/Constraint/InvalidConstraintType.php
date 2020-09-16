<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Constraint;

final class InvalidConstraintType extends \Graphpinator\Exception\Parser\ParserError
{
    public const MESSAGE = 'Invalid constraint type.';
}
