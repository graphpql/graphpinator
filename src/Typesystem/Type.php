<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem;

use Graphpinator\Graphpinator;
use Graphpinator\Typesystem\Contract\AbstractType;
use Graphpinator\Typesystem\Contract\ConcreteType;
use Graphpinator\Typesystem\Contract\InterfaceImplementor;
use Graphpinator\Typesystem\Contract\NamedTypeVisitor;
use Graphpinator\Typesystem\Contract\Type as TypeContract;
use Graphpinator\Typesystem\Contract\TypeConditionable;
use Graphpinator\Typesystem\DirectiveUsage\DirectiveUsage;
use Graphpinator\Typesystem\DirectiveUsage\DirectiveUsageSet;
use Graphpinator\Typesystem\Exception\DirectiveIncorrectType;
use Graphpinator\Typesystem\Exception\InterfaceOrTypeMustDefineOneOrMoreFields;
use Graphpinator\Typesystem\Field\ResolvableField;
use Graphpinator\Typesystem\Field\ResolvableFieldSet;
use Graphpinator\Typesystem\Location\ObjectLocation;
use Graphpinator\Typesystem\Utils\THasDirectives;
use Graphpinator\Typesystem\Utils\TInterfaceImplementor;
use Graphpinator\Typesystem\Utils\TMetaFields;

abstract class Type extends ConcreteType implements
    TypeConditionable,
    InterfaceImplementor
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

    final public function isInstanceOf(TypeContract $type) : bool
    {
        if ($type instanceof AbstractType) {
            return $type->isImplementedBy($this);
        }

        return parent::isInstanceOf($type);
    }

    final public function getFields() : ResolvableFieldSet
    {
        if (!$this->fields instanceof ResolvableFieldSet) {
            $this->fields = $this->getFieldDefinition();

            if (Graphpinator::$validateSchema) {
                if ($this->fields->count() === 0) {
                    throw new InterfaceOrTypeMustDefineOneOrMoreFields();
                }

                $this->validateInterfaceContract();
            }

            $this->inheritDescriptions();
        }

        \assert($this->fields instanceof ResolvableFieldSet);

        return $this->fields;
    }

    final public function accept(NamedTypeVisitor $visitor) : mixed
    {
        return $visitor->visitType($this);
    }

    final public function addDirective(
        ObjectLocation $directive,
        array $arguments = [],
    ) : static
    {
        $usage = new DirectiveUsage($directive, $arguments);

        if (Graphpinator::$validateSchema && !$directive->validateObjectUsage($this, $usage->getArgumentValues())) {
            throw new DirectiveIncorrectType();
        }

        $this->directiveUsages[] = $usage;

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
