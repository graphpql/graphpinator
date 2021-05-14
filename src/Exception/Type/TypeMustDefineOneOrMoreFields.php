<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Type;

final class TypeMustDefineOneOrMoreFields extends \Graphpinator\Exception\Type\TypeError
{
    public const MESSAGE = 'An Object type must define one or more fields.';
}
