<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Normalizer;

final class UnknownFragment extends \Graphpinator\Exception\Normalizer\NormalizerError
{
    public const MESSAGE = 'Fragment "%s" is not defined in request.';

    public function __construct(string $fragmentName)
    {
        $this->messageArgs = [$fragmentName];

        parent::__construct();
    }
}
