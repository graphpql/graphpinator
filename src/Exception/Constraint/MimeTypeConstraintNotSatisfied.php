<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Constraint;

final class MimeTypeConstraintNotSatisfied extends \Graphpinator\Exception\Constraint\ConstraintError
{
    public const MESSAGE = 'Mime type constraint was not satisfied.';
}
