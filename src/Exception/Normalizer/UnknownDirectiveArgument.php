<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Normalizer;

final class UnknownDirectiveArgument extends \Graphpinator\Exception\Resolver\ResolverError
{
    public const MESSAGE = 'Unknown argument "%s" provided for "@%s".';

    public function __construct(string $argument, string $directive)
    {
        $this->messageArgs = [$argument, $directive];

        parent::__construct();
    }
}
