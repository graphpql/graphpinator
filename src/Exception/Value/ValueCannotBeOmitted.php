<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Value;

final class ValueCannotBeOmitted extends ValueError
{
    public const MESSAGE = 'Argument value cannot be omitted.';

    public function __construct()
    {
        parent::__construct(true);
    }
}
