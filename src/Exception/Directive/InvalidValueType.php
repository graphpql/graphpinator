<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Directive;

final class InvalidValueType extends \Graphpinator\Exception\Directive\DirectiveError
{
    public const MESSAGE = 'Invalid type of value.';
}
