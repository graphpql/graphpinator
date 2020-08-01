<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Type;

final class ExpectedListOrNull extends \Graphpinator\Exception\Type\TypeError
{
    public const MESSAGE = 'Value must be list or null.';
}
