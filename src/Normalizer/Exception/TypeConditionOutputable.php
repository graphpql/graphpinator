<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Exception;

final class TypeConditionOutputable extends \Graphpinator\Normalizer\Exception\NormalizerError
{
    public const MESSAGE = 'Fragment type condition must be outputable composite type.';
}
