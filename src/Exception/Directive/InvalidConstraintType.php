<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Directive;

final class InvalidConstraintType extends \Graphpinator\Exception\Constraint\ConstraintSettingsError
{
    public const MESSAGE = 'Directive is used on incompatible type.';
}