<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Normalizer;

final class InvalidFragmentType extends \Graphpinator\Exception\Normalizer\NormalizerError
{
    public const MESSAGE = 'Invalid fragment type condition.';
}
