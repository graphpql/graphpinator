<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem;

use \Graphpinator\Typesystem\Contract\AbstractType;
use \Graphpinator\Typesystem\Contract\InterfaceImplementor;
use \Graphpinator\Typesystem\Contract\NamedTypeVisitor;
use \Graphpinator\Typesystem\DirectiveUsage\DirectiveUsage;
use \Graphpinator\Typesystem\DirectiveUsage\DirectiveUsageSet;
use \Graphpinator\Typesystem\Exception\DirectiveIncorrectType;
use \Graphpinator\Typesystem\Exception\InterfaceCycle;
use \Graphpinator\Typesystem\Exception\InterfaceOrTypeMustDefineOneOrMoreFields;
use \Graphpinator\Typesystem\Field\FieldSet;
use \Graphpinator\Typesystem\Location\ObjectLocation;
use \Graphpinator\Typesystem\Utils\TInterfaceImplementor;

abstract class InterfaceType extends AbstractType implements
    InterfaceImplementor
{
    use TInterfaceImplementor;
    use \Graphpinator\Typesystem\Utils\TMetaFields;
    use \Graphpinator\Typesystem\Utils\THasDirectives;

    private bool $cycleValidated = false;

    public function __construct(?\Graphpinator\Typesystem\InterfaceSet $implements = null)
    {
        $this->implements = $implements
            ?? new \Graphpinator\Typesystem\InterfaceSet([]);
        $this->directiveUsages = new DirectiveUsageSet();
    }

    final public function isInstanceOf(\Graphpinator\Typesystem\Contract\Type $type) : bool
    {
        if ($type instanceof NotNullType) {
            return $this->isInstanceOf($type->getInnerType());
        }

        return $type instanceof static
            || ($type instanceof self && $this->implements($type));
    }

    final public function isImplementedBy(\Graphpinator\Typesystem\Contract\Type $type) : bool
    {
        if ($type instanceof NotNullType) {
            return $this->isImplementedBy($type->getInnerType());
        }

        return $type instanceof InterfaceImplementor
            && $type->implements($this);
    }

    final public function getFields() : FieldSet
    {
        if (!$this->fields instanceof FieldSet) {
            $this->fields = new FieldSet([]);

            foreach ($this->implements as $interfaceType) {
                $this->fields->merge($interfaceType->getFields(), true);
            }

            $this->fields->merge($this->getFieldDefinition(), true);

            if (\Graphpinator\Graphpinator::$validateSchema) {
                if ($this->fields->count() === 0) {
                    throw new InterfaceOrTypeMustDefineOneOrMoreFields();
                }

                $this->validateInterfaceContract();
                $this->validateCycles([]);
            }
        }

        return $this->fields;
    }

    final public function accept(NamedTypeVisitor $visitor) : mixed
    {
        return $visitor->visitInterface($this);
    }

    final public function addDirective(
        ObjectLocation $directive,
        array $arguments = [],
    ) : static
    {
        $usage = new DirectiveUsage($directive, $arguments);

        if (\Graphpinator\Graphpinator::$validateSchema && !$directive->validateObjectUsage($this, $usage->getArgumentValues())) {
            throw new DirectiveIncorrectType();
        }

        $this->directiveUsages[] = $usage;

        return $this;
    }

    abstract protected function getFieldDefinition() : FieldSet;

    private function validateCycles(array $stack) : void
    {
        if ($this->cycleValidated) {
            return;
        }

        if (\array_key_exists($this->getName(), $stack)) {
            throw new InterfaceCycle();
        }

        $stack[$this->getName()] = true;

        foreach ($this->implements as $implementedInterface) {
            $implementedInterface->validateCycles($stack);
        }

        unset($stack[$this->getName()]);
        $this->cycleValidated = true;
    }
}
