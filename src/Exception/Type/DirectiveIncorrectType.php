<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Type;

final class DirectiveIncorrectType extends \Graphpinator\Exception\Type\TypeError
{
    public const MESSAGE = 'Directive cannot be used on this type or has incompatible settings.';
}
