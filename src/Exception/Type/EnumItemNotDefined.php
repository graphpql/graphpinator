<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Type;

final class EnumItemNotDefined extends \Graphpinator\Exception\Type\TypeError
{
    public const MESSAGE = 'EnumItem is not defined.';
}
