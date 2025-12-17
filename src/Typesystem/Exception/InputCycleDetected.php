<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Exception;

final class InputCycleDetected extends TypeError
{
    public const MESSAGE = 'Input cycle detected (%s).';

    /**
     * @param list<string> $inputCycle
     */
    public function __construct(
        array $inputCycle,
    )
    {
        parent::__construct([\implode(' -> ', $inputCycle)]);
    }
}
