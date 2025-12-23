<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

use Graphpinator\Normalizer\Exception\VariableTypeMismatch;
use Graphpinator\Normalizer\Variable\Variable;
use Graphpinator\Normalizer\VariableValueSet;
use Graphpinator\Typesystem\Contract\Type;
use Graphpinator\Typesystem\Visitor\IsInputableVisitor;
use Graphpinator\Typesystem\Visitor\IsInstanceOfVisitor;
use Graphpinator\Value\Contract\InputedValue;
use Graphpinator\Value\Contract\InputedValueVisitor;

final class VariableValue implements InputedValue
{
    private ?InputedValue $value = null;

    public function __construct(
        public readonly Type $type,
        public readonly Variable $variable,
    )
    {
        $isInputable = $this->type->accept(new IsInputableVisitor());
        $isCompatible = $variable->type->accept(new IsInstanceOfVisitor($type));

        if (!$isInputable || !$isCompatible) {
            throw new VariableTypeMismatch();
        }
    }

    #[\Override]
    public function accept(InputedValueVisitor $visitor) : mixed
    {
        return $visitor->visitVariable($this);
    }

    #[\Override]
    public function getRawValue() : mixed
    {
        return $this->value->getRawValue();
    }

    #[\Override]
    public function getType() : Type
    {
        return $this->type;
    }

    public function setVariableValue(VariableValueSet $variables) : void
    {
        $this->value = $variables->get($this->variable->name);
    }

    public function getConcreteValue() : InputedValue
    {
        return $this->value;
    }
}
