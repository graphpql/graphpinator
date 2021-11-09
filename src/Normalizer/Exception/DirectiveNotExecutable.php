<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Exception;

final class DirectiveNotExecutable extends NormalizerError
{
    public const MESSAGE = 'Directive "%s" is not executable directive.';

    public function __construct(string $name)
    {
        $this->messageArgs = [$name];

        parent::__construct();
    }
}
