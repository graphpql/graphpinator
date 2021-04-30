<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

final class ArgumentValue
{
    use \Nette\SmartObject;

    public function __construct(
        private \Graphpinator\Argument\Argument $argument,
        private \Graphpinator\Value\InputedValue $value,
        private bool $hasVariables,
    )
    {
        if (!$this->hasVariables) {
            $this->resolvePureDirectives();
        }
    }

    public function getValue() : \Graphpinator\Value\InputedValue
    {
        return $this->value;
    }

    public function getArgument() : \Graphpinator\Argument\Argument
    {
        return $this->argument;
    }

    public function applyVariables(\Graphpinator\Normalizer\VariableValueSet $variables) : void
    {
        if ($this->hasVariables) {
            $this->value->applyVariables($variables);
            $this->resolvePureDirectives();
        }
    }

    public function resolvePureDirectives() : void
    {
        foreach ($this->argument->getDirectiveUsages() as $directiveUsage) {
            $directive = $directiveUsage->getDirective();
            \assert($directive instanceof \Graphpinator\Directive\Contract\ArgumentDefinitionLocation);

            if ($directive::isPure()) {
                $directive->resolveArgumentDefinition($directiveUsage->getArgumentValues(), $this);
            }
        }
    }

    public function resolveNonPureDirectives() : void
    {
        $this->value->resolveRemainingDirectives();

        foreach ($this->argument->getDirectiveUsages() as $directiveUsage) {
            $directive = $directiveUsage->getDirective();
            \assert($directive instanceof \Graphpinator\Directive\Contract\ArgumentDefinitionLocation);

            if (!$directive::isPure()) {
                $directive->resolveArgumentDefinition($directiveUsage->getArgumentValues(), $this);
            }
        }
    }
}
