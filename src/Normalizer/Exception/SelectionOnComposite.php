<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Exception;

final class SelectionOnComposite extends \Graphpinator\Normalizer\Exception\NormalizerError
{
    public const MESSAGE = 'Composite type without fields specified.';
}
