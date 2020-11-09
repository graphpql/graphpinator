<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Normalizer;

final class UnknownField extends \Graphpinator\Exception\Normalizer\NormalizerError
{
    public const MESSAGE = 'Unknown field "%s" requested for type "%s".';

    public function __construct(string $field, string $type)
    {
        $this->messageArgs = [$field, $type];

        parent::__construct();
    }
}
