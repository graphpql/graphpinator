<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Type;

final class ObjectConstraintsNotPreserved extends \Graphpinator\Exception\Type\TypeError
{
    public const MESSAGE = 'Object constraints must be preserved during inheritance.';
}
