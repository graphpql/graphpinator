<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Parser;

final class ExpectedFragmentSpreadInfo extends ParserError
{
    public const MESSAGE = 'Expected fragment name or inline fragment.';
}
