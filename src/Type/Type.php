<?php

declare(strict_types = 1);

namespace Graphpinator\Type;

abstract class Type extends \Graphpinator\Type\Contract\ConcreteDefinition implements
    \Graphpinator\Type\Contract\Resolvable,
    \Graphpinator\Type\Contract\TypeConditionable,
    \Graphpinator\Type\Contract\InterfaceImplementor
{
    use \Graphpinator\Type\Contract\TInterfaceImplementor;
    use \Graphpinator\Type\Contract\TMetaFields;
    use \Graphpinator\Utils\TObjectConstraint;
    use \Graphpinator\Printable\TRepeatablePrint;

    public function __construct(?\Graphpinator\Utils\InterfaceSet $implements = null)
    {
        $this->implements = $implements
            ?? new \Graphpinator\Utils\InterfaceSet([]);
    }

    abstract public function validateNonNullValue(mixed $rawValue) : bool;

    final public function createResolvedValue(mixed $rawValue) : \Graphpinator\Value\ResolvedValue
    {
        if ($rawValue === null) {
            return new \Graphpinator\Value\NullResolvedValue($this);
        }

        return new \Graphpinator\Value\TypeIntermediateValue($this, $rawValue);
    }

    final public function resolve(
        ?\Graphpinator\Normalizer\Field\FieldSet $requestedFields,
        \Graphpinator\Value\ResolvedValue $parentResult
    ) : \Graphpinator\Value\TypeValue
    {
        \assert($requestedFields instanceof \Graphpinator\Normalizer\Field\FieldSet);
        $resolved = new \stdClass();

        foreach ($requestedFields as $field) {
            if ($field->getTypeCondition() instanceof \Graphpinator\Type\Contract\NamedDefinition &&
                !$parentResult->getType()->isInstanceOf($field->getTypeCondition())) {
                continue;
            }

            foreach ($field->getDirectives() as $directive) {
                $directiveDef = $directive->getDirective();
                $arguments = $directive->getArguments();
                $directiveResult = $directiveDef->resolveFieldBefore($arguments);

                if ($directiveResult === \Graphpinator\Directive\FieldDirectiveResult::SKIP) {
                    continue 2;
                }
            }

            $fieldDef = $this->getMetaFields()[$field->getName()]
                ?? $this->getFields()[$field->getName()];
            $fieldResult = $fieldDef->resolve($parentResult, $field);

            foreach ($field->getDirectives() as $directive) {
                $directiveDef = $directive->getDirective();
                $arguments = $directive->getArguments();
                $directiveResult = $directiveDef->resolveFieldAfter($fieldResult, $arguments);

                if ($directiveResult === \Graphpinator\Directive\FieldDirectiveResult::SKIP) {
                    continue 2;
                }
            }

            $resolved->{$field->getAlias()} = $fieldResult;
        }

        return new \Graphpinator\Value\TypeValue($this, $resolved);
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

    final public function printSchema() : string
    {
        return $this->printDescription()
            . 'type ' . $this->getName() . $this->printImplements() . $this->printConstraints() . ' {' . \PHP_EOL
            . $this->printItems($this->getFields(), 1)
            . '}';
    }

    abstract protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet;
}
