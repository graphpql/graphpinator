<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Type;

final class ObjectConstraintsNotEqual extends \Graphpinator\Exception\Type\TypeError
{
    public const MESSAGE = 'Object constraints are not equal.';
}
