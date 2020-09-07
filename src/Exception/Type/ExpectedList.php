<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Type;

final class ExpectedList extends \Graphpinator\Exception\Type\TypeError
{
    public const MESSAGE = 'Value has to be list.';
}
