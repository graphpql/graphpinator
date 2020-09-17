<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Constraint;

final class NegativeLengthParameter extends \Graphpinator\Exception\Constraint\ConstraintSettingsError
{
    public const MESSAGE = 'Invalid length argument passed to minLength/maxLength constraint.';
}
