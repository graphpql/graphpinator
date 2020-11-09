<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Normalizer;

final class UnknownType extends \Graphpinator\Exception\Resolver\ResolverError
{
    public const MESSAGE = 'Unknown type "%s".';

    public function __construct(string $type)
    {
        $this->messageArgs = [$type];

        parent::__construct();
    }
}
