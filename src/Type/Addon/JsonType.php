<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

final class JsonType extends \Graphpinator\Type\Scalar\ScalarType
{
    protected const NAME = 'Json';
    protected const DESCRIPTION = 'Json type - string which contains valid JSON.';

    protected function validateNonNullValue($rawValue) : bool
    {
        try {
            return \is_string($rawValue)
                && \Graphpinator\Json::fromString($rawValue)->toObject();
        } catch (\JsonException $e) {
            return false;
        }
    }
}
