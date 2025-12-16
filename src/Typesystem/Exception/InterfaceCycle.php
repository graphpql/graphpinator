<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Exception;

final class InterfaceCycle extends TypeError
{
    public const MESSAGE = 'Interface implement cycle detected (%s).';

    /**
     * @param list<string> $interfaceCycle
     */
    public function __construct(
        array $interfaceCycle,
    )
    {
        parent::__construct([\implode(' -> ', $interfaceCycle)]);
    }
}
