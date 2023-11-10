<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Exception;

final class InvalidFragmentType extends \Graphpinator\Normalizer\Exception\NormalizerError
{
    public const MESSAGE = 'Invalid fragment type condition. ("%s" is not instance of "%s").';

    public function __construct(string $childType, string $parentType)
    {
        parent::__construct([$childType, $parentType]);
    }
}
