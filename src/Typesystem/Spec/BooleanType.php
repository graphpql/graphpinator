<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Spec;

use Graphpinator\Typesystem\Attribute\Description;
use Graphpinator\Typesystem\ScalarType;

/**
 * @extends ScalarType<bool>
 */
#[Description('Boolean built-in type')]
final class BooleanType extends ScalarType
{
    protected const NAME = 'Boolean';

    #[\Override]
    public function validateAndCoerceInput(mixed $rawValue) : ?bool
    {
        return \is_bool($rawValue)
            ? $rawValue
            : null;
    }

    #[\Override]
    public function coerceOutput(mixed $rawValue) : bool
    {
        return $rawValue;
    }
}
