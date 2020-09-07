<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Parser;

final class FragmentSpreadNotDefined extends \Graphpinator\Exception\Parser\ParserError
{
    public const MESSAGE = 'FragmentSpread is not defined.';
}
