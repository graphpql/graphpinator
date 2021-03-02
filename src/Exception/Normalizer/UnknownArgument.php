<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Normalizer;

final class UnknownArgument extends \Graphpinator\Exception\Resolver\ResolverError
{
    public const MESSAGE = 'Unknown argument "%s" provided.';

    public function __construct(string $argument)
    {
        $this->messageArgs = [$argument];

        parent::__construct();
    }
}
