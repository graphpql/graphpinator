<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Constraint;

final class UniqueConstraintOnlyScalar extends \Graphpinator\Exception\Parser\ParserError
{
    public const MESSAGE = 'Unique constraint supports only scalars.';

    protected function isOutputable() : bool
    {
        return false;
    }
}
