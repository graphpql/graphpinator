<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Normalizer;

final class DirectiveNotDefined extends \Graphpinator\Exception\Normalizer\NormalizerError
{
    public const MESSAGE = 'Directive is not defined.';
}
