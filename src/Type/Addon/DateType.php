<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

final class DateType extends \Graphpinator\Type\Scalar\ScalarType
{
    protected const NAME = 'Date';
    protected const DESCRIPTION = 'This add on scalar validates date string input with format d-m-Y.
    Examples - 01-01-2010, 02-02-2015, 20-20-2015';

    protected function validateNonNullValue($rawValue) : bool
    {
        return \is_string($rawValue) &&
            \Nette\Utils\DateTime::createFromFormat('d-m-Y', $rawValue) instanceof \Nette\Utils\DateTime;
    }
}
