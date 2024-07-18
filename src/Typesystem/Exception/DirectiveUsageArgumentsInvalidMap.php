<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Exception;

final class DirectiveUsageArgumentsInvalidMap extends TypeError
{
    public const MESSAGE = 'DirectiveUsage arguments array must be map (key => value), list given.';
}
