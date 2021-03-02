<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Normalizer;

final class DirectiveNotExecutable extends \Graphpinator\Exception\Normalizer\NormalizerError
{
    public const MESSAGE = 'Directive "%s" is not executable directive.';

    public function __construct(string $name)
    {
        $this->messageArgs = [$name];

        parent::__construct();
    }
}
