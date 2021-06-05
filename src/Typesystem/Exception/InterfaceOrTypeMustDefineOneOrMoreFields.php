<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Exception;

final class InterfaceOrTypeMustDefineOneOrMoreFields extends \Graphpinator\Typesystem\Exception\TypeError
{
    public const MESSAGE = 'An Object type or interface must define one or more fields.';
}
