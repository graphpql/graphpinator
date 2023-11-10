<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Exception;

final class DirectiveIncorrectUsage extends \Graphpinator\Normalizer\Exception\NormalizerError
{
    public const MESSAGE = 'Directive "%s" cannot be used on this field, check for additional directive requirements.';

    public function __construct(string $name)
    {
        parent::__construct([$name]);
    }
}
