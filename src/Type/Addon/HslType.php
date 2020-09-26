<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

final class HslType extends \Graphpinator\Type\Scalar\ScalarType
{
    protected const NAME = 'HSL';
    protected const DESCRIPTION = 'HSL built-in type';

    protected function validateNonNullValue($rawValue) : bool
    {
        if (!\array_key_exists('hue', $rawValue) ||
            !\array_key_exists('saturation', $rawValue) ||
            !\array_key_exists('lightness', $rawValue)) {
            return false;
        }

        foreach ($rawValue as $key => $value) {
            if ($key === 'hue' && ($value > 360 || $value < 0)) {
                return false;
            }

            if (($key === 'saturation' || $key === 'lightness') && ($value > 100 || $value < 0)) {
                return false;
            }
        }

        return true;
    }
}
