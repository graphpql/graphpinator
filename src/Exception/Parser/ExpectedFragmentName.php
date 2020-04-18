<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Parser;

final class ExpectedFragmentName extends ParserError
{
    public const MESSAGE = 'Expected fragment name.';
}
