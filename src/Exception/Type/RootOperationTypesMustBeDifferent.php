<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Type;

final class RootOperationTypesMustBeDifferent extends \Graphpinator\Exception\Type\TypeError
{
    public const MESSAGE = 'The query, mutation, and subscription root types must all be different types if provided.';
}
