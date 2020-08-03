<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Parser;

final class DirectiveNotDefined extends \Graphpinator\Exception\Parser\ParserError
{
    public const MESSAGE = 'Directive is not defined.';
}
