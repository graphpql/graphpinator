<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Normalizer;

final class UnknownArgument extends \Graphpinator\Exception\Resolver\ResolverError
{
    public const MESSAGE = 'Unknown argument "%s" provided for field/directive "%s".';

    public function __construct(string $argument, string $name)
    {
        $this->messageArgs = [$argument, $name];

        parent::__construct();
    }
}
