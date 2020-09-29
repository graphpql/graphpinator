<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

final class HslaType extends \Graphpinator\Type\Scalar\ScalarType
{
    protected const NAME = 'HSLA';
    protected const DESCRIPTION = 'HSLA built-in type';

    protected function validateNonNullValue($rawValue) : bool
    {
        if (!\is_array($rawValue)) {
            return false;
        }

        if (!\array_key_exists('hue', $rawValue) ||
            !\array_key_exists('saturation', $rawValue) ||
            !\array_key_exists('lightness', $rawValue) ||
            !\array_key_exists('alpha', $rawValue)) {
            return false;
        }

        if (!\is_int($rawValue['hue']) ||
            !\is_int($rawValue['saturation']) ||
            !\is_int($rawValue['lightness'])) {
            return false;
        }

        if (!\is_float($rawValue['alpha']) && !\is_int($rawValue['alpha'])) {
            return false;
        }

        foreach ($rawValue as $key => $value) {
            if ($key === 'hue' && ($value > 360 || $value < 0)) {
                return false;
            }

            if (($key === 'saturation' || $key === 'lightness') && ($value > 100 || $value < 0)) {
                return false;
            }

            if ($key === 'alpha' && ($value > 1 || $value < 0)) {
                return false;
            }
        }

        return true;
    }
}
