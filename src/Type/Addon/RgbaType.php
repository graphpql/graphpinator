<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

final class RgbaType extends \Graphpinator\Type\Scalar\ScalarType
{
    protected const NAME = 'Rgba';
    protected const DESCRIPTION = 'This add on scalar validates rgba array input with keys and its values -
    red (0-255), green (0-255), blue (0-255), alpha (0-1).
    Examples - [\'red\' => 100, \'green\' => 50, \'blue\' => 50, \'alpha\' => 0.5],
               [\'red\' => 255, \'green\' => 255, \'blue\' => 255, \'alpha\' => 1.0],
               [\'red\' => 0, \'green\' => 0, \'blue\' => 0, \'alpha\' => 0.0]';

    protected function validateNonNullValue($rawValue) : bool
    {
        return \is_array($rawValue)
            && \array_key_exists('red', $rawValue)
            && \array_key_exists('green', $rawValue)
            && \array_key_exists('blue', $rawValue)
            && \array_key_exists('alpha', $rawValue)
            && \is_int($rawValue['red'])
            && \is_int($rawValue['green'])
            && \is_int($rawValue['blue'])
            && \is_float($rawValue['alpha'])
            && $rawValue['red'] <= 255
            && $rawValue['red'] >= 0
            && $rawValue['green'] <= 255
            && $rawValue['green'] >= 0
            && $rawValue['blue'] <= 255
            && $rawValue['blue'] >= 0
            && $rawValue['alpha'] <= 1
            && $rawValue['alpha'] >= 0;
    }
}
