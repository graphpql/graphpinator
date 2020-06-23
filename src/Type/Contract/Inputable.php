<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Contract;

interface Inputable extends \Graphpinator\Type\Contract\Definition
{
    //@phpcs:ignore SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingNativeTypeHint
    public function applyDefaults($value);
}
