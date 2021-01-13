<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Directive;

final class Directive
{
    use \Nette\SmartObject;

    private \Graphpinator\Directive\ExecutableDirective $directive;
    private \Graphpinator\Value\ArgumentValueSet $arguments;

    public function __construct(
        \Graphpinator\Parser\Directive\Directive $parsed,
        \Graphpinator\Container\Container $typeContainer,
        \Graphpinator\Normalizer\Variable\VariableSet $variableSet,
    )
    {
        $directive = $typeContainer->getDirective($parsed->getName());

        if (!$directive instanceof \Graphpinator\Directive\Directive) {
            throw new \Graphpinator\Exception\Normalizer\UnknownDirective($parsed->getName());
        }

        if (!$directive instanceof \Graphpinator\Directive\ExecutableDirective) {
            throw new \Graphpinator\Exception\Normalizer\DirectiveNotExecutable();
        }

        $this->directive = $directive;
        $this->arguments = new \Graphpinator\Value\ArgumentValueSet(
            $parsed->getArguments() instanceof \Graphpinator\Parser\Value\ArgumentValueSet
                ? $parsed->getArguments()
                : new \Graphpinator\Parser\Value\ArgumentValueSet([]),
            $directive,
            $variableSet,
        );
    }

    public function getDirective() : \Graphpinator\Directive\ExecutableDirective
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
