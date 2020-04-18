<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Resolver;

abstract class ResolverError extends \Exception
{
    public const MESSAGE = '';

    public function __construct()
    {
        parent::__construct(static::MESSAGE);
    }
}
