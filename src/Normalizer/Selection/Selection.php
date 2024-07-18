<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Selection;

use Graphpinator\Normalizer\VariableValueSet;

interface Selection
{
    public function applyVariables(VariableValueSet $variables) : void;

    public function accept(SelectionVisitor $visitor) : mixed;
}
