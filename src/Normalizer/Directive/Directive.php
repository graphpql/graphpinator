<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Directive;

final class Directive
{
    use \Nette\SmartObject;

    public function __construct(
        private \Graphpinator\Directive\Contract\ExecutableDefinition $directive,
        private \Graphpinator\Value\ArgumentValueSet $arguments,
    ) {}

    public function getDirective() : \Graphpinator\Directive\Contract\ExecutableDefinition
    {
        return $this->directive;
    }

    public function getArguments() : \Graphpinator\Value\ArgumentValueSet
    {
        return $this->arguments;
    }

    public function applyVariables(
        \Graphpinator\Normalizer\VariableValueSet $variables
    ) : void
    {
        $this->arguments->applyVariables($variables);
    }
}
