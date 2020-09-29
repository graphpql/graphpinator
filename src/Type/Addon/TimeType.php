<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

final class TimeType extends \Graphpinator\Type\Scalar\ScalarType
{
    protected const NAME = 'Time';
    protected const DESCRIPTION = 'Time built-in type';

    protected function validateNonNullValue($rawValue) : bool
    {
        if (!\is_string($rawValue)) {
            return false;
        }

        return \Nette\Utils\DateTime::createFromFormat('H:i:s', $rawValue) instanceof \Nette\Utils\DateTime;
    }
}
