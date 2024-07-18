<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Exception;

final class DirectiveIncorrectType extends TypeError
{
    public const MESSAGE = 'Directive cannot be used on this type or has incompatible settings.';
}
