<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem;

abstract class InterfaceType extends \Graphpinator\Typesystem\Contract\AbstractType implements
    \Graphpinator\Typesystem\Contract\InterfaceImplementor
{
    use \Graphpinator\Typesystem\Utils\TInterfaceImplementor;
    use \Graphpinator\Typesystem\Utils\TMetaFields;
    use \Graphpinator\Typesystem\Utils\THasDirectives;

    private bool $cycleValidated = false;

    public function __construct(?\Graphpinator\Typesystem\InterfaceSet $implements = null)
    {
        $this->implements = $implements
            ?? new \Graphpinator\Typesystem\InterfaceSet([]);
        $this->directiveUsages = new \Graphpinator\Typesystem\DirectiveUsage\DirectiveUsageSet();
    }

    final public function isInstanceOf(\Graphpinator\Typesystem\Contract\Type $type) : bool
    {
        return $type instanceof static
            || ($type instanceof self && $this->implements($type));
    }

    final public function isImplementedBy(\Graphpinator\Typesystem\Contract\Type $type) : bool
    {
        if ($type instanceof NotNullType) {
            return $this->isImplementedBy($type->getInnerType());
        }

        return $type instanceof \Graphpinator\Typesystem\Contract\InterfaceImplementor
            && $type->implements($this);
    }

    final public function getFields() : \Graphpinator\Typesystem\Field\FieldSet
    {
        if (!$this->fields instanceof \Graphpinator\Typesystem\Field\FieldSet) {
            $this->fields = new \Graphpinator\Typesystem\Field\FieldSet([]);

            foreach ($this->implements as $interfaceType) {
                $this->fields->merge($interfaceType->getFields(), true);
            }

            $this->fields->merge($this->getFieldDefinition(), true);

            if (\Graphpinator\Graphpinator::$validateSchema) {
                if ($this->fields->count() === 0) {
                    throw new \Graphpinator\Typesystem\Exception\InterfaceOrTypeMustDefineOneOrMoreFields();
                }

                $this->validateInterfaceContract();
                $this->validateCycles();
            }
        }

        return $this->fields;
    }

    final public function accept(\Graphpinator\Typesystem\Contract\NamedTypeVisitor $visitor) : mixed
    {
        return $visitor->visitInterface($this);
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

    abstract protected function getFieldDefinition() : \Graphpinator\Typesystem\Field\FieldSet;

    private function validateCycles(array $stack = []) : void
    {
        if ($this->cycleValidated) {
            return;
        }

        if (\array_key_exists($this->getName(), $stack)) {
            throw new \Graphpinator\Typesystem\Exception\InterfaceCycle(\array_keys($stack));
        }

        $stack[$this->getName()] = true;

        foreach ($this->implements as $implementedInterface) {
            $implementedInterface->validateCycles($stack);
        }

        unset($stack[$this->getName()]);
        $this->cycleValidated = true;
    }
}
