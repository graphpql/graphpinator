<?php

declare(strict_types = 1);

namespace Graphpinator\Exception;

final class FloatLiteralNegativeFraction extends \Exception
{
    public function __construct(int $position)
    {
        parent::__construct('Negative fraction part in Float value.', $position);
    }
}
