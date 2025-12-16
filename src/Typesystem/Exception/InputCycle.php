<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Exception;

final class InputCycle extends TypeError
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
