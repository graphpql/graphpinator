<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

final class HslType extends \Graphpinator\Type\Scalar\ScalarType
{
    protected const NAME = 'hsl';
    protected const DESCRIPTION = 'This add on scalar validates hsl array input with keys and its values -
    hue (0-360), saturation (0-100), lightness (0-100).
    Examples - [\'hue\' => 180, \'saturation\' => 50, \'lightness\' => 50],
               [\'hue\' => 360, \'saturation\' => 100, \'lightness\' => 100],
               [\'hue\' => 0, \'saturation\' => 0, \'lightness\' => 0]';

    protected function validateNonNullValue($rawValue) : bool
    {
        return \is_array($rawValue)
            && \array_key_exists('hue', $rawValue)
            && \array_key_exists('saturation', $rawValue)
            && \array_key_exists('lightness', $rawValue)
            && \is_int($rawValue['hue'])
            && \is_int($rawValue['saturation'])
            && \is_int($rawValue['lightness'])
            && $rawValue['hue'] <= 360
            && $rawValue['hue'] >= 0
            && $rawValue['saturation'] <= 100
            && $rawValue['saturation'] >= 0
            && $rawValue['lightness'] <= 100
            && $rawValue['lightness'] >= 0;
    }
}
