<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

final class DateTimeType extends \Graphpinator\Type\Scalar\ScalarType
{
    protected const NAME = 'DateTime';
    protected const DESCRIPTION = 'DateTime type - string which contains valid date in "<YYYY>-<MM>-<DD> <HH>:<MM>:<SS>" format.';

    protected function validateNonNullValue($rawValue) : bool
    {
        return \is_string($rawValue)
            && \Nette\Utils\DateTime::createFromFormat('Y-m-d H:i:s', $rawValue) instanceof \Nette\Utils\DateTime;
    }
}
