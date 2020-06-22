<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\Value;

interface Value
{
    //@phpcs:ignore SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingNativeTypeHint
    public function getRawValue();

    public function applyVariables(\Graphpinator\Resolver\VariableValueSet $variables) : self;
}
