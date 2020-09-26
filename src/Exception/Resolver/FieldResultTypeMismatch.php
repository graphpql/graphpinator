<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Resolver;

final class FieldResultTypeMismatch extends \Graphpinator\Exception\Resolver\ResolverError
{
    public const MESSAGE = 'FieldResult doesnt match field type.';

    public function isOutputable() : bool
    {
        return false;
    }
}
