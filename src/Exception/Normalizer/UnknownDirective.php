<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Normalizer;

final class UnknownDirective extends \Graphpinator\Exception\Resolver\ResolverError
{
    public const MESSAGE = 'Unknown directive "%s".';

    public function __construct(string $name)
    {
        $this->messageArgs = [$name];

        parent::__construct();
    }
}
