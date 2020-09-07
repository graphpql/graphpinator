<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Resolver;

final class InputValueIsReadOnly extends \Graphpinator\Exception\Resolver\ResolverError
{
    public const MESSAGE = 'InputValue is read-only.';
}
