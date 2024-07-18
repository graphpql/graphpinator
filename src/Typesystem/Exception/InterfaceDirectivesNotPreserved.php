<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Exception;

final class InterfaceDirectivesNotPreserved extends TypeError
{
    public const MESSAGE = 'Interface directives must be preserved during inheritance (invariance).';
}
