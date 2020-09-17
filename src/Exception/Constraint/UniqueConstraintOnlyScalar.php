<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Constraint;

final class UniqueConstraintOnlyScalar extends \Graphpinator\Exception\Constraint\ConstraintSettingsError
{
    public const MESSAGE = 'Unique constraint supports only scalars.';
}
