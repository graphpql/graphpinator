<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

final class RgbType extends \Graphpinator\Type\Scalar\ScalarType
{
    protected const NAME = 'RGB';
    protected const DESCRIPTION = 'RGB built-in type';

    protected function validateNonNullValue($rawValue) : bool
    {
        if (!\is_array($rawValue)) {
            return false;
        }

        if (!\array_key_exists('red', $rawValue) ||
            !\array_key_exists('green', $rawValue) ||
            !\array_key_exists('blue', $rawValue)) {
            return false;
        }

        foreach ($rawValue as $key => $value) {
            if (!\is_int($value)) {
                return false;
            }

            if ($value > 255 || $value < 0) {
                return false;
            }
        }

        return true;
    }
}
