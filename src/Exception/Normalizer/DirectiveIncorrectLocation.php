<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Normalizer;

final class DirectiveIncorrectLocation extends \Graphpinator\Exception\Normalizer\NormalizerError
{
    public const MESSAGE = 'Directive "%s" cannot be used on this DirectiveLocation.';

    public function __construct(string $name)
    {
        $this->messageArgs = [$name];

        parent::__construct();
    }
}
