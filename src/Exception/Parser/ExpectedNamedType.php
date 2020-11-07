<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Parser;

final class ExpectedNamedType extends \Graphpinator\Exception\Parser\ExpectedError
{
    public const MESSAGE = 'Expected named type without type modifiers, got "%s".';
}
