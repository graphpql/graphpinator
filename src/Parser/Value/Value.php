<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\Value;

interface Value
{
    public function getRawValue();

    public function applyVariables(\Graphpinator\Request\VariableValueSet $variables) : self;
}
