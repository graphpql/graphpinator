<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\Value;

interface Value
{
    public function normalize(\Graphpinator\Value\ValidatedValueSet $variables) : self;

    public function getRawValue();
}
