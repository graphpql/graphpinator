<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Exception;

final class InputCycle extends \Graphpinator\Typesystem\Exception\TypeError
{
    public const MESSAGE = 'Input cycle detected (%s).';

    public function __construct(array $inputCycle)
    {
        parent::__construct([\implode(' -> ', $inputCycle)]);
    }
}
