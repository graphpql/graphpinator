<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

final class JsonType extends \Graphpinator\Type\Scalar\ScalarType
{
    protected const NAME = 'JSON';
    protected const DESCRIPTION = 'JSON built-in type';

    protected function validateNonNullValue($rawValue) : bool
    {
        return \Graphpinator\Json::fromObject($rawValue)->isValid() || \Graphpinator\Json::fromString($rawValue)->isValid();
    }
}
