<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Constraint;

final class InnerListNotList extends \Graphpinator\Exception\Parser\ParserError
{
    public const MESSAGE = 'Invalid constraint type.';

    protected function isOutputable() : bool
    {
        return false;
    }
}
