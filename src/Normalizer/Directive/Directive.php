<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Directive;

final class Directive
{
    use \Nette\SmartObject;

    private \Graphpinator\Directive\Directive $directive;
    private \Graphpinator\Parser\Value\NamedValueSet $arguments;

    public function __construct(
        \Graphpinator\Directive\Directive $directive,
        \Graphpinator\Parser\Value\NamedValueSet $arguments
    ) {
        $this->directive = $directive;
        $this->arguments = $arguments;
    }

    public function getDirective() : \Graphpinator\Directive\Directive
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
