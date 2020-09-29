<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

final class DateType extends \Graphpinator\Type\Scalar\ScalarType
{
    protected const NAME = 'Date';
    protected const DESCRIPTION = 'Date built-in type';

    protected function validateNonNullValue($rawValue) : bool
    {
        if (!\is_string($rawValue)) {
            return false;
        }

        return \Nette\Utils\DateTime::createFromFormat('d-m-Y', $rawValue) instanceof \Nette\Utils\DateTime;
    }
}
