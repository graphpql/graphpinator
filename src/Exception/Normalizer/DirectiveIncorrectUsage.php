<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Normalizer;

final class DirectiveIncorrectUsage extends \Graphpinator\Exception\Normalizer\NormalizerError
{
    public const MESSAGE = 'Directive "%s" cannot be used on this field, check for additional directive requirements.';

    public function __construct(string $name)
    {
        $this->messageArgs = [$name];

        parent::__construct();
    }
}
