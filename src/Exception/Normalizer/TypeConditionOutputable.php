<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Normalizer;

final class TypeConditionOutputable extends \Graphpinator\Exception\Normalizer\NormalizerError
{
    public const MESSAGE = 'Fragment type condition must be outputable type.';
}
