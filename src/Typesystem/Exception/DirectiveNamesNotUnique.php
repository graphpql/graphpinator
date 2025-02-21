<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Exception;

final class DirectiveNamesNotUnique extends TypeError
{
    public const MESSAGE = 'Directive names are not unique.';
}
