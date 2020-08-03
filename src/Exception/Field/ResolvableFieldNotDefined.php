<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Field;

final class ResolvableFieldNotDefined extends \Graphpinator\Exception\Parser\ParserError
{
    public const MESSAGE = 'ResolvableField is not defined.';
}
