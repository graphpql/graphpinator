<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

final class RgbaType extends \Graphpinator\Type\Scalar\ScalarType
{
    protected const NAME = 'RGBA';
    protected const DESCRIPTION = 'RGBA built-in type';

    protected function validateNonNullValue($rawValue) : bool
    {
        if (!\is_array($rawValue)) {
            return false;
        }

        if (!\array_key_exists('red', $rawValue) ||
            !\array_key_exists('green', $rawValue) ||
            !\array_key_exists('blue', $rawValue) ||
            !\array_key_exists('alpha', $rawValue)) {
            return false;
        }

        if (!\is_int($rawValue['red']) ||
            !\is_int($rawValue['green']) ||
            !\is_int($rawValue['blue'])) {
            return false;
        }

        if (!\is_float($rawValue['alpha']) && !\is_int($rawValue['alpha'])) {
            return false;
        }

        foreach ($rawValue as $key => $value) {
            if ($value > 255 || $value < 0) {
                return false;
            }

            if ($key === 'alpha' && ($value > 1 || $value < 0)) {
                return false;
            }
        }

        return true;
    }
}
