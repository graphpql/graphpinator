<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Directive;

final class Directive
{
    use \Nette\SmartObject;

    private \Graphpinator\Directive\ExecutableDirective $directive;
    private \Graphpinator\Normalizer\Value\ArgumentValueSet $arguments;

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
        $this->arguments = $parserDirective->getArguments() instanceof \Graphpinator\Parser\Value\ArgumentValueSet
            ? $parserDirective->getArguments()->normalize($directive->getArguments(), $typeContainer)
            : new \Graphpinator\Normalizer\Value\ArgumentValueSet();

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

    public function getArguments() : \Graphpinator\Normalizer\Value\ArgumentValueSet
    {
        return $this->arguments;
    }

    public function applyVariables(
        \Graphpinator\Resolver\VariableValueSet $variables
    ) : void
    {
        $this->arguments->applyVariables($variables);
    }
}
