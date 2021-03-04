<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Exception;

final class UnknownInputField extends \Graphpinator\Normalizer\Exception\NormalizerError
{
    public const MESSAGE = 'Unknown input field "%s" requested for type "%s".';

    public function __construct(string $field, string $type)
    {
        $this->messageArgs = [$field, $type];

        parent::__construct();
    }
}
