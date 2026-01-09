<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Exception;

final class ArgumentDefaultValueCycleDetected extends TypeError
{
    public const MESSAGE = 'Argument default value cycle detected (%s).';

    public function __construct(
        string $inputName,
    )
    {
        parent::__construct([$inputName]);
    }
}
