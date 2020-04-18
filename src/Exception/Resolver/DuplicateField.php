<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Resolver;

final class DuplicateField extends ResolverError
{
    public const MESSAGE = 'Duplicated field introduced in fragment.';
}
