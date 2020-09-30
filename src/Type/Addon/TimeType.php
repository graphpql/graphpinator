<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

final class TimeType extends \Graphpinator\Type\Scalar\ScalarType
{
    protected const NAME = 'Time';
    protected const DESCRIPTION = 'This add on scalar validates time string input with format H:i:s.
    Examples - 05:05:20, 10:10:50, 00:00:00';

    protected function validateNonNullValue($rawValue) : bool
    {
        return \is_string($rawValue) &&
            \Nette\Utils\DateTime::createFromFormat('H:i:s', $rawValue) instanceof \Nette\Utils\DateTime;
    }
}
