<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Type;

final class FailedToCreateListValue extends \Graphpinator\Exception\Type\TypeError
{
    public const MESSAGE = 'Cannot create list.';
}
