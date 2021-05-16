<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Type;

final class InterfaceCycle extends \Graphpinator\Exception\Type\TypeError
{
    public const MESSAGE = 'Interface implement cycle detected.';
}
