<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Parser;

final class FragmentNotDefined extends \Graphpinator\Exception\Parser\ParserError
{
    public const MESSAGE = 'Fragment is not defined.';
}
