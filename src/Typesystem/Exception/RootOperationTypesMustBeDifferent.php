<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Exception;

final class RootOperationTypesMustBeDifferent extends \Graphpinator\Typesystem\Exception\TypeError
{
    public const MESSAGE = 'The query, mutation, and subscription root types must all be different types if provided.';
}
