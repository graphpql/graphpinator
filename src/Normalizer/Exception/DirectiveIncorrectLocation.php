<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Exception;

final class DirectiveIncorrectLocation extends NormalizerError
{
    public const MESSAGE = 'Directive "%s" cannot be used on this DirectiveLocation.';

    public function __construct(string $name)
    {
        parent::__construct([$name]);
    }
}
