<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem;

abstract class Type extends \Graphpinator\Typesystem\Contract\ConcreteType implements
    \Graphpinator\Typesystem\Contract\TypeConditionable,
    \Graphpinator\Typesystem\Contract\InterfaceImplementor
{
    use \Graphpinator\Typesystem\Utils\TInterfaceImplementor;
    use \Graphpinator\Typesystem\Utils\TMetaFields;
    use \Graphpinator\Typesystem\Utils\THasDirectives;

    public function __construct(
        InterfaceSet $implements = new InterfaceSet([]),
    )
    {
        $this->implements = $implements;
        $this->directiveUsages = new \Graphpinator\Typesystem\DirectiveUsage\DirectiveUsageSet();
    }

    abstract public function validateNonNullValue(mixed $rawValue) : bool;

    final public function addMetaField(\Graphpinator\Typesystem\Field\ResolvableField $field) : void
    {
        $this->getMetaFields()->offsetSet($field->getName(), $field);
    }

    final public function isInstanceOf(\Graphpinator\Typesystem\Contract\Type $type) : bool
    {
        if ($type instanceof \Graphpinator\Typesystem\Contract\AbstractType) {
            return $type->isImplementedBy($this);
        }

        return parent::isInstanceOf($type);
    }

    final public function getFields() : \Graphpinator\Typesystem\Field\ResolvableFieldSet
    {
        if (!$this->fields instanceof \Graphpinator\Typesystem\Field\ResolvableFieldSet) {
            $this->fields = $this->getFieldDefinition();

            if (\Graphpinator\Graphpinator::$validateSchema) {
                if ($this->fields->count() === 0) {
                    throw new \Graphpinator\Typesystem\Exception\InterfaceOrTypeMustDefineOneOrMoreFields();
                }

                $this->validateInterfaceContract();
            }

            $this->inheritDescriptions();
        }

        \assert($this->fields instanceof \Graphpinator\Typesystem\Field\ResolvableFieldSet);

        return $this->fields;
    }

    final public function accept(\Graphpinator\Typesystem\Contract\NamedTypeVisitor $visitor) : mixed
    {
        return $visitor->visitType($this);
    }

    final public function addDirective(
        \Graphpinator\Typesystem\Location\ObjectLocation $directive,
        array $arguments = [],
    ) : static
    {
        $usage = new \Graphpinator\Typesystem\DirectiveUsage\DirectiveUsage($directive, $arguments);

        if (\Graphpinator\Graphpinator::$validateSchema && !$directive->validateObjectUsage($this, $usage->getArgumentValues())) {
            throw new \Graphpinator\Typesystem\Exception\DirectiveIncorrectType();
        }

        $this->directiveUsages[] = $usage;

        return $this;
    }

    abstract protected function getFieldDefinition() : \Graphpinator\Typesystem\Field\ResolvableFieldSet;

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
