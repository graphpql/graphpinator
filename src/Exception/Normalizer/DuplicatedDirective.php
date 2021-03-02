<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Normalizer;

final class DuplicatedDirective extends \Graphpinator\Exception\Normalizer\NormalizerError
{
    public const MESSAGE = 'Duplicated directive "%s" which is not repeatable.';

    public function __construct(string $name)
    {
        $this->messageArgs = [$name];

        parent::__construct();
    }
}
