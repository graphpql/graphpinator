<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Directive;

use \Graphpinator\Typesystem\Contract\ExecutableDirective;

final class Directive
{
    use \Nette\SmartObject;

    public function __construct(
        private ExecutableDirective $directive,
        private \Graphpinator\Value\ArgumentValueSet $arguments,
    )
    {
    }

    public function getDirective() : ExecutableDirective
    {
        return $this->directive;
    }

    public function getArguments() : \Graphpinator\Value\ArgumentValueSet
    {
        return $this->arguments;
    }

    public function applyVariables(
        \Graphpinator\Normalizer\VariableValueSet $variables,
    ) : void
    {
        $this->arguments->applyVariables($variables);
    }
}
