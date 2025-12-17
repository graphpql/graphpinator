<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem;

use Graphpinator\Typesystem\Contract\InterfaceImplementor;
use Graphpinator\Typesystem\Contract\NamedType;
use Graphpinator\Typesystem\Contract\NamedTypeVisitor;
use Graphpinator\Typesystem\Contract\TypeConditionable;
use Graphpinator\Typesystem\DirectiveUsage\DirectiveUsage;
use Graphpinator\Typesystem\DirectiveUsage\DirectiveUsageSet;
use Graphpinator\Typesystem\Field\ResolvableField;
use Graphpinator\Typesystem\Field\ResolvableFieldSet;
use Graphpinator\Typesystem\Location\ObjectLocation;
use Graphpinator\Typesystem\Utils\THasDirectives;
use Graphpinator\Typesystem\Utils\TInterfaceImplementor;
use Graphpinator\Typesystem\Utils\TMetaFields;

abstract class Type extends NamedType implements TypeConditionable, InterfaceImplementor
{
    use TInterfaceImplementor;
    use TMetaFields;
    use THasDirectives;

    public function __construct(
        InterfaceSet $implements = new InterfaceSet([]),
    )
    {
        $this->implements = $implements;
        $this->directiveUsages = new DirectiveUsageSet();
    }

    abstract public function validateNonNullValue(mixed $rawValue) : bool;

    final public function addMetaField(ResolvableField $field) : void
    {
        $this->getMetaFields()->offsetSet($field->getName(), $field);
    }

    #[\Override]
    final public function getFields() : ResolvableFieldSet
    {
        if (!$this->fields instanceof ResolvableFieldSet) {
            $this->fields = $this->getFieldDefinition();
            $this->inheritDescriptions();
        }

        \assert($this->fields instanceof ResolvableFieldSet);

        return $this->fields;
    }

    #[\Override]
    final public function accept(NamedTypeVisitor $visitor) : mixed
    {
        return $visitor->visitType($this);
    }

    /**
     * @param ObjectLocation $directive
     * @phpcs:ignore
     * @param array<string, mixed> $arguments
     */
    final public function addDirective(ObjectLocation $directive, array $arguments = []) : static
    {
        $this->directiveUsages[] = new DirectiveUsage($directive, $arguments);

        return $this;
    }

    abstract protected function getFieldDefinition() : ResolvableFieldSet;

    private function inheritDescriptions() : void
    {
        foreach ($this->implements as $interfaceType) {
            foreach ($interfaceType->getFields() as $interfaceField) {
                $currentField = $this->fields[$interfaceField->getName()];

                if ($currentField->getDescription() === null && $interfaceField->getDescription() !== null) {
                    $currentField->setDescription($interfaceField->getDescription());
                }

                foreach ($interfaceField->getArguments() as $interfaceArgument) {
                    $currentArgument = $currentField->getArguments()[$interfaceArgument->getName()];

                    if ($currentArgument->getDescription() === null && $interfaceArgument->getDescription() !== null) {
                        $currentArgument->setDescription($interfaceArgument->getDescription());
                    }
                }
            }
        }
    }
}
