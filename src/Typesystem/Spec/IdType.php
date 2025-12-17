<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Spec;

use Graphpinator\Typesystem\Attribute\Description;
use Graphpinator\Typesystem\ScalarType;

/**
 * @extends ScalarType<string>
 */
#[Description('ID built-in type')]
final class IdType extends ScalarType
{
    protected const NAME = 'ID';

    #[\Override]
    public function validateAndCoerceInput(mixed $rawValue) : ?string
    {
        // coerce int to string
        $rawValue = \is_int($rawValue)
            ? (string) $rawValue
            : $rawValue;

        return \is_string($rawValue)
            ? $rawValue
            : null;
    }

    #[\Override]
    public function coerceOutput(mixed $rawValue) : string
    {
        return $rawValue;
    }
}
