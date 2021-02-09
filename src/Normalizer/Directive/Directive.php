<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Directive;

final class Directive
{
    use \Nette\SmartObject;

    private \Graphpinator\Directive\Contract\ExecutableDefinition $directive;
    private \Graphpinator\Value\ArgumentValueSet $arguments;

    public function __construct(
        \Graphpinator\Parser\Directive\Directive $parsed,
        \Graphpinator\Type\Contract\Definition $scopeType,
        \Graphpinator\Container\Container $typeContainer,
        \Graphpinator\Normalizer\Variable\VariableSet $variableSet,
    )
    {
        $directive = $typeContainer->getDirective($parsed->getName());

        if (!$directive instanceof \Graphpinator\Directive\Directive) {
            throw new \Graphpinator\Exception\Normalizer\UnknownDirective($parsed->getName());
        }

        if (!$directive instanceof \Graphpinator\Directive\Contract\ExecutableDefinition) {
            throw new \Graphpinator\Exception\Normalizer\DirectiveNotExecutable();
        }

        if (!\in_array($parsed->getLocation(), $directive->getLocations(), true)) {
            throw new \Graphpinator\Exception\Normalizer\DirectiveIncorrectLocation();
        }

        $this->directive = $directive;
        $this->arguments = \Graphpinator\Value\ArgumentValueSet::fromParsed(
            $parsed->getArguments()
                ?? new \Graphpinator\Parser\Value\ArgumentValueSet([]),
            $directive,
            $variableSet,
        );

        if (!$directive->validateType($scopeType, $this->arguments)) {
            throw new \Graphpinator\Exception\Normalizer\DirectiveIncorrectType();
        }
    }

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
