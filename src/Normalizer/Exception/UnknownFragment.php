<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Exception;

final class UnknownFragment extends \Graphpinator\Normalizer\Exception\NormalizerError
{
    public const MESSAGE = 'Fragment "%s" is not defined in request.';

    public function __construct(string $fragmentName)
    {
        parent::__construct([$fragmentName]);
    }
}
