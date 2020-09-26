<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Constraint;

final class InvalidConstraintType extends \Graphpinator\Exception\Constraint\ConstraintSettingsError
{
    public const MESSAGE = 'Invalid constraint type.';
}
