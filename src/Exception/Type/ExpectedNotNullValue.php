<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Type;

final class ExpectedNotNullValue extends \Graphpinator\Exception\Type\TypeError
{
    public const MESSAGE = 'Value cannot be null.';
}
