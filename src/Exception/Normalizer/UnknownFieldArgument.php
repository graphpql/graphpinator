<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Normalizer;

final class UnknownFieldArgument extends \Graphpinator\Exception\Resolver\ResolverError
{
    public const MESSAGE = 'Unknown argument "%s" provided for "%s"::"%s".';

    public function __construct(string $argument, string $field, string $type)
    {
        $this->messageArgs = [$argument, $type, $field];

        parent::__construct();
    }
}
