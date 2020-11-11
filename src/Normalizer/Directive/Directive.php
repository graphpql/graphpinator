<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Directive;

final class Directive
{
    use \Nette\SmartObject;

    private \Graphpinator\Directive\ExecutableDirective $directive;
    private \Graphpinator\Parser\Value\NamedValueSet $arguments;

    public function __construct(
        \Graphpinator\Parser\Directive\Directive $parserDirective,
        \Graphpinator\Container\Container $typeContainer
    )
    {
        $directive = $typeContainer->getDirective($parserDirective->getName());

        if (!$directive instanceof \Graphpinator\Directive\Directive) {
            throw new \Graphpinator\Exception\Normalizer\UnknownDirective($parserDirective->getName());
        }

        if (!$directive instanceof \Graphpinator\Directive\ExecutableDirective) {
            throw new \Graphpinator\Exception\Normalizer\DirectiveNotExecutable();
        }

        $this->directive = $directive;
        $this->arguments = $parserDirective->getArguments()
            ?? new \Graphpinator\Parser\Value\NamedValueSet();

        foreach ($this->arguments as $argument) {
            if (!$directive->getArguments()->offsetExists($argument->getName())) {
                throw new \Graphpinator\Exception\Normalizer\UnknownDirectiveArgument($argument->getName(), $directive->getName());
            }
        }
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
        $clone = clone $this;
        $clone->arguments = $this->arguments->applyVariables($variables);

        return $clone;
    }
}
