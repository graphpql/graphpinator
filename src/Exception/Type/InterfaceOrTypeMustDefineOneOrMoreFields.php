<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Type;

final class InterfaceOrTypeMustDefineOneOrMoreFields extends \Graphpinator\Exception\Type\TypeError
{
    public const MESSAGE = 'An Object type or interface must define one or more fields.';
}
