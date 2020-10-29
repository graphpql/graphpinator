<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Directive;

final class Directive
{
    use \Nette\SmartObject;

    private \Graphpinator\Directive\ExecutableDirective $directive;
    private \Graphpinator\Parser\Value\NamedValueSet $arguments;

    public function __construct(
        \Graphpinator\Directive\ExecutableDirective $directive,
        ?\Graphpinator\Parser\Value\NamedValueSet $arguments = null
    )
    {
        $this->directive = $directive;
        $this->arguments = $arguments
            ?? new \Graphpinator\Parser\Value\NamedValueSet([]);
    }

    public function getDirective() : \Graphpinator\Directive\ExecutableDirective
    {
        return $this->directive;
    }

    public function getArguments() : \Graphpinator\Parser\Value\NamedValueSet
    {
        return $this->arguments;
    }

    public function applyVariables(
        \Graphpinator\Resolver\VariableValueSet $variables
    ) : self
    {
        return new self(
            $this->directive,
            $this->arguments->applyVariables($variables),
        );
    }
}
