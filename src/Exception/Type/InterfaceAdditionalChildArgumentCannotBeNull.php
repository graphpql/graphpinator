<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Type;

class InterfaceAdditionalChildArgumentCannotBeNull extends \Graphpinator\Exception\Type\TypeError
{
    public const MESSAGE = 'Additional arguments for the interface\'s children cannot be null';
}
