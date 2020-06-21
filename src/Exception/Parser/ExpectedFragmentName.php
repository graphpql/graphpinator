<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Parser;

final class ExpectedFragmentName extends \Graphpinator\Exception\Parser\ParserError
{
    public const MESSAGE = 'Expected fragment name.';
}
