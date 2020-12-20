<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Directive;

final class InvalidFieldOffset extends \Graphpinator\Exception\Directive\DirectiveError
{
    public const MESSAGE = 'Invalid field offset.';
}
