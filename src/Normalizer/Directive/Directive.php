<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Directive;

use Graphpinator\Normalizer\VariableValueSet;
use Graphpinator\Typesystem\Contract\ExecutableDirective;
use Graphpinator\Value\ArgumentValueSet;

final class Directive
{
    public function __construct(
        private ExecutableDirective $directive,
        private ArgumentValueSet $arguments,
    )
    {
    }

    public function getDirective() : ExecutableDirective
    {
        return $this->directive;
    }

    public function getArguments() : ArgumentValueSet
    {
        return $this->arguments;
    }

    public function applyVariables(
        VariableValueSet $variables,
    ) : void
    {
        $this->arguments->applyVariables($variables);
    }
}
