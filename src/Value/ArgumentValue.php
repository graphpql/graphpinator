<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

use Graphpinator\Normalizer\VariableValueSet;
use Graphpinator\Typesystem\Argument\Argument;
use Graphpinator\Typesystem\Location\ArgumentDefinitionLocation;

final class ArgumentValue
{
    public function __construct(
        private Argument $argument,
        private InputedValue $value,
        private bool $hasVariables,
    )
    {
        if (!$this->hasVariables) {
            $this->resolvePureDirectives();
        }
    }

    public function getValue() : InputedValue
    {
        return $this->value;
    }

    public function getArgument() : Argument
    {
        return $this->argument;
    }

    public function applyVariables(VariableValueSet $variables) : void
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
            \assert($directive instanceof ArgumentDefinitionLocation);

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
            \assert($directive instanceof ArgumentDefinitionLocation);

            if (!$directive::isPure()) {
                $directive->resolveArgumentDefinition($directiveUsage->getArgumentValues(), $this);
            }
        }
    }
}
