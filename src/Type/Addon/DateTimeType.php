<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

final class DateTimeType extends \Graphpinator\Type\Scalar\ScalarType
{
    protected const NAME = 'dateTime';
    protected const DESCRIPTION = 'This add on scalar validates date-time string input with format d-m-Y H:i:s.
    Examples - 01-01-2010 05:05:20, 02-02-2015 10:10:50, 20-20-2015 00:00:00';

    protected function validateNonNullValue($rawValue) : bool
    {
        return \is_string($rawValue) &&
            \Nette\Utils\DateTime::createFromFormat('d-m-Y H:i:s', $rawValue) instanceof \Nette\Utils\DateTime;
    }
}
