<?php

declare(strict_types = 1);

namespace Graphpinator\Type;

abstract class Type extends \Graphpinator\Type\Contract\ConcreteDefinition implements
    \Graphpinator\Type\Contract\TypeConditionable,
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

    abstract public function validateNonNullValue(mixed $rawValue) : bool;

    final public function createResolvedValue(mixed $rawValue) : \Graphpinator\Value\ResolvedValue
    {
        if ($rawValue === null) {
            return new \Graphpinator\Value\NullResolvedValue($this);
        }

        return new \Graphpinator\Value\TypeIntermediateValue($this, $rawValue);
    }

    final public function addMetaField(\Graphpinator\Field\ResolvableField $field) : void
    {
        $this->getMetaFields()->offsetSet($field->getName(), $field);
    }

    final public function isInstanceOf(\Graphpinator\Type\Contract\Definition $type) : bool
    {
        if ($type instanceof \Graphpinator\Type\Contract\AbstractDefinition) {
            return $type->isImplementedBy($this);
        }

        return parent::isInstanceOf($type);
    }

    final public function getFields() : \Graphpinator\Field\ResolvableFieldSet
    {
        if (!$this->fields instanceof \Graphpinator\Field\ResolvableFieldSet) {
            $this->fields = $this->getFieldDefinition();

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
        return \Graphpinator\Type\Introspection\TypeKind::OBJECT;
    }

    final public function accept(\Graphpinator\Typesystem\NamedTypeVisitor $visitor) : mixed
    {
        return $visitor->visitType($this);
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

    abstract protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet;
}
