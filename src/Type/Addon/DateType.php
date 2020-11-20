<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

final class DateType extends \Graphpinator\Type\Scalar\ScalarType
{
    protected const NAME = 'Date';
    protected const DESCRIPTION = 'Date type - string which contains valid date in "<YYYY>-<MM>-<DD>" format.';

    protected function validateNonNullValue(mixed $rawValue) : bool
    {
        return \is_string($rawValue)
            && \Nette\Utils\DateTime::createFromFormat('Y-m-d', $rawValue) instanceof \Nette\Utils\DateTime;
    }
}
