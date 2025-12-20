<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

use Graphpinator\Normalizer\Exception\VariableTypeMismatch;
use Graphpinator\Normalizer\Variable\Variable;
use Graphpinator\Normalizer\VariableValueSet;
use Graphpinator\Typesystem\Contract\Type;
use Graphpinator\Typesystem\Visitor\IsInputableVisitor;
use Graphpinator\Typesystem\Visitor\IsInstanceOfVisitor;

final class VariableValue implements InputedValue
{
    private ?InputedValue $value = null;

    public function __construct(
        private Type $type,
        private Variable $variable,
    )
    {
        $isInputable = $this->type->accept(new IsInputableVisitor());
        $isCompatible = $variable->type->accept(new IsInstanceOfVisitor($type));

        if (!$isInputable || !$isCompatible) {
            throw new VariableTypeMismatch();
        }
    }

    #[\Override]
    public function getRawValue(bool $forResolvers = false) : mixed
    {
        return $this->value->getRawValue($forResolvers);
    }

    public function getConcreteValue() : InputedValue
    {
        return $this->value;
    }

    public function getVariable() : Variable
    {
        return $this->variable;
    }

    #[\Override]
    public function getType() : Type
    {
        return $this->type;
    }

    #[\Override]
    public function printValue() : string
    {
        throw new \RuntimeException('Not implemented');
    }

    #[\Override]
    public function applyVariables(VariableValueSet $variables) : void
    {
        $this->value = $variables->get($this->variable->name);
    }

    #[\Override]
    public function resolveRemainingDirectives() : void
    {
        $this->value->resolveRemainingDirectives();
    }

    #[\Override]
    public function isSame(Value $compare) : bool
    {
        return $compare instanceof self
            && $compare->variable->name === $this->variable->name;
    }
}
