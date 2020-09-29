<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

final class DateTimeType extends \Graphpinator\Type\Scalar\ScalarType
{
    protected const NAME = 'DateTime';
    protected const DESCRIPTION = 'DateTime built-in type';

    protected function validateNonNullValue($rawValue) : bool
    {
        if (!\is_string($rawValue)) {
            return false;
        }

        return \Nette\Utils\DateTime::createFromFormat('d-m-Y H:i:s', $rawValue) instanceof \Nette\Utils\DateTime;
    }
}
