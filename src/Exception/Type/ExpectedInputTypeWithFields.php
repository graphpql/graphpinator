<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Type;

final class ExpectedInputTypeWithFields extends \Graphpinator\Exception\Type\TypeError
{
    public const MESSAGE = 'Composite input type without fields specified.';
}
