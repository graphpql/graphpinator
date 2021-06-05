<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Directive;

final class Directive
{
    use \Nette\SmartObject;

    public function __construct(
        private \Graphpinator\Typesystem\Contract\ExecutableDirective $directive,
        private \Graphpinator\Value\ArgumentValueSet $arguments,
    )
    {
    }

    public function getDirective() : \Graphpinator\Typesystem\Contract\ExecutableDirective
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
