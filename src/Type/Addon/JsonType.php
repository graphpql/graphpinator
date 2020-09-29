<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

final class JsonType extends \Graphpinator\Type\Scalar\ScalarType
{
    protected const NAME = 'json';
    protected const DESCRIPTION = 'This add on scalar validates json string input and its accurate format.
    Examples - \'{"testName":"testValue"}\', \'{"testName2":"420"}\'';

    protected function validateNonNullValue($rawValue) : bool
    {
        return \is_string($rawValue) &&
            \Graphpinator\Json::fromString($rawValue)->isValid();
    }
}
