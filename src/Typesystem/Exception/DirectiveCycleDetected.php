<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Exception;

final class DirectiveCycleDetected extends TypeError
{
    public const MESSAGE = 'Directive cycle detected (%s).';

    /**
     * @param list<string> $directiveCycle
     */
    public function __construct(
        array $directiveCycle,
    )
    {
        parent::__construct([\implode(' -> ', $directiveCycle)]);
    }
}
