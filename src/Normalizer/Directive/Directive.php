<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Directive;

use Graphpinator\Normalizer\VariableValueSet;
use Graphpinator\Typesystem\Contract\ExecutableDirective;
use Graphpinator\Value\ArgumentValueSet;

final readonly class Directive
{
    public function __construct(
        public ExecutableDirective $directive,
        public ArgumentValueSet $arguments,
    )
    {
    }

    public function applyVariables(VariableValueSet $variables) : void
    {
        $this->arguments->applyVariables($variables);
    }
}
