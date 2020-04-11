<?php

declare(strict_types = 1);

namespace Graphpinator\Exception;

final class IntLiteralLeadingZero extends \Exception
{
    public function __construct(int $position)
    {
        parent::__construct('Int literal with leading zeroes.', $position);
    }
}
