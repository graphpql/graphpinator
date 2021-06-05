<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Type;

final class InterfaceDirectivesNotPreserved extends \Graphpinator\Exception\Type\TypeError
{
    public const MESSAGE = 'Interface directives must be preserved during inheritance (invariance).';
}
