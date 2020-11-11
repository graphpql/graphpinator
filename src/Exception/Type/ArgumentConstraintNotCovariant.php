<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Type;

final class ArgumentConstraintNotCovariant extends \Graphpinator\Exception\Type\TypeError
{
    public const MESSAGE = 'Argument constraint is not covariant.';
}
