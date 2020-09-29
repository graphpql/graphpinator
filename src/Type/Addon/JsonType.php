<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

final class JsonType extends \Graphpinator\Type\Scalar\ScalarType
{
    protected const NAME = 'JSON';
    protected const DESCRIPTION = 'JSON built-in type';

    protected function validateNonNullValue($rawValue) : bool
    {
        if (\is_array($rawValue) && \count($rawValue) > 0) {
            return \Infinityloop\Utils\Json::fromArray($rawValue)->isValid();
        }

        if (\is_string($rawValue)) {
            return \Graphpinator\Json::fromString($rawValue)->isValid();
        }

        if (\is_object($rawValue)) {
            return \Graphpinator\Json::fromObject((object) $rawValue)->isValid();
        }

        return false;
    }
}
