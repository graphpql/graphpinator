<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Tokenizer;

final class InvalidEllipsis extends \Graphpinator\Exception\Tokenizer\TokenizerError
{
    public const MESSAGE = 'Invalid ellipsis - three dots are expected for ellipsis.';
}
