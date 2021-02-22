<?php

declare(strict_types = 1);

namespace Graphpinator\Type;

abstract class InterfaceType extends \Graphpinator\Type\Contract\AbstractDefinition implements
    \Graphpinator\Type\Contract\InterfaceImplementor
{
    use \Graphpinator\Type\Contract\TInterfaceImplementor;
    use \Graphpinator\Type\Contract\TMetaFields;
    use \Graphpinator\Directive\THasDirectives;

    public function __construct(?\Graphpinator\Utils\InterfaceSet $implements = null)
    {
        $this->implements = $implements
            ?? new \Graphpinator\Utils\InterfaceSet([]);
        $this->directiveUsages = new \Graphpinator\Directive\DirectiveUsageSet();
    }

    final public function isInstanceOf(\Graphpinator\Type\Contract\Definition $type) : bool
    {
        if ($type instanceof NotNullType) {
            return $this->isInstanceOf($type->getInnerType());
        }

        return $type instanceof static
            || ($type instanceof self && $this->implements($type));
    }

    final public function isImplementedBy(\Graphpinator\Type\Contract\Definition $type) : bool
    {
        if ($type instanceof NotNullType) {
            return $this->isImplementedBy($type->getInnerType());
        }

        return $type instanceof \Graphpinator\Type\Contract\InterfaceImplementor
            && $type->implements($this);
    }

    final public function getFields() : \Graphpinator\Field\FieldSet
    {
        if (!$this->fields instanceof \Graphpinator\Field\FieldSet) {
            $this->fields = new \Graphpinator\Field\FieldSet([]);

            foreach ($this->implements as $interfaceType) {
                $this->fields->merge($interfaceType->getFields(), true);
            }

            $this->fields->merge($this->getFieldDefinition(), true);

            $this->validateInterfaces();
        }

        return $this->fields;
    }

    final public function getField(string $name) : \Graphpinator\Field\Field
    {
        return $this->getMetaFields()[$name]
            ?? $this->getFields()[$name]
            ?? throw new \Graphpinator\Exception\Normalizer\UnknownField($name, $this->getName());
    }

    final public function getTypeKind() : string
    {
        return \Graphpinator\Type\Introspection\TypeKind::INTERFACE;
    }

    final public function accept(\Graphpinator\Typesystem\NamedTypeVisitor $visitor) : mixed
    {
        return $visitor->visitInterface($this);
    }

    final public function addDirective(
        \Graphpinator\Directive\Contract\ObjectLocation $directive,
        array $arguments = [],
    ) : static
    {
        $usage = new \Graphpinator\Directive\DirectiveUsage($directive, $arguments);

        if (!$directive->validateType($this, $usage->getArgumentValues())) {
            throw new \Graphpinator\Exception\Type\DirectiveIncorrectType();
        }

        $this->directiveUsages[] = $usage;

        return $this;
    }

    abstract protected function getFieldDefinition() : \Graphpinator\Field\FieldSet;
}
