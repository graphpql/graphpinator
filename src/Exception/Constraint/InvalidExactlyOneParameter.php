<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Constraint;

final class InvalidExactlyOneParameter extends \Graphpinator\Exception\Constraint\ConstraintSettingsError
{
    public const MESSAGE = 'Invalid array passed as exactlyOne constraint.';
}
