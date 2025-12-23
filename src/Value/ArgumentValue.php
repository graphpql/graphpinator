<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

use Graphpinator\Normalizer\VariableValueSet;
use Graphpinator\Typesystem\Argument\Argument;
use Graphpinator\Typesystem\Location\ArgumentDefinitionLocation;
use Graphpinator\Value\Contract\InputedValue;
use Graphpinator\Value\Visitor\ApplyVariablesVisitor;
use Graphpinator\Value\Visitor\ResolveNonPureDirectivesVisitor;

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
            $this->value->accept(new ApplyVariablesVisitor($variables));
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
        $this->value->accept(new ResolveNonPureDirectivesVisitor());

        foreach ($this->argument->getDirectiveUsages() as $directiveUsage) {
            $directive = $directiveUsage->getDirective();
            \assert($directive instanceof ArgumentDefinitionLocation);

            if (!$directive::isPure()) {
                $directive->resolveArgumentDefinition($directiveUsage->getArgumentValues(), $this);
            }
        }
    }
}
