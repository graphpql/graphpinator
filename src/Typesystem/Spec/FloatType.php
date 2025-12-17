<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Spec;

use Graphpinator\Typesystem\Attribute\Description;
use Graphpinator\Typesystem\ScalarType;

/**
 * @extends ScalarType<float>
 */
#[Description('Float built-in type')]
final class FloatType extends ScalarType
{
    protected const NAME = 'Float';

    #[\Override]
    public function validateAndCoerceInput(mixed $rawValue) : ?float
    {
        // coerce int to float
        $rawValue = \is_int($rawValue)
            ? (float) $rawValue
            : $rawValue;

        return \is_float($rawValue) && \is_finite($rawValue)
            ? $rawValue
            : null;
    }

    #[\Override]
    public function coerceOutput(mixed $rawValue) : float
    {
        return $rawValue;
    }
}
