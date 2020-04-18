<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Resolver;

final class FieldResultTypeMismatch extends ResolverError
{
    public const MESSAGE = 'FieldResult doesnt match field type.';
}
