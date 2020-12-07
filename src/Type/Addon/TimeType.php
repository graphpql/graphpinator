<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

final class TimeType extends \Graphpinator\Type\Scalar\ScalarType
{
    protected const NAME = 'Time';
    protected const DESCRIPTION = 'Time type - string which contains time in "<HH>:<MM>:<SS>" format.';

    public function validateNonNullValue(mixed $rawValue) : bool
    {
        return \is_string($rawValue)
            && \Nette\Utils\DateTime::createFromFormat('H:i:s', $rawValue) instanceof \Nette\Utils\DateTime;
    }
}
