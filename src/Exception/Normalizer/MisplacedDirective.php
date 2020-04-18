<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Normalizer;

final class MisplacedDirective extends NormalizerError
{
    public const MESSAGE = 'Directive cannot be used on this DirectiveLocation.';
}
