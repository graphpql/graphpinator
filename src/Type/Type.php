<?php

declare(strict_types = 1);

namespace Graphpinator\Type;

abstract class Type extends \Graphpinator\Type\Contract\ConcreteDefinition implements
    \Graphpinator\Type\Contract\Resolvable,
    \Graphpinator\Type\Contract\InterfaceImplementor
{
    use \Graphpinator\Type\Contract\TResolvable;
    use \Graphpinator\Type\Contract\TInterfaceImplementor;
    use \Graphpinator\Utils\TObjectConstraint;
    use \Graphpinator\Printable\TRepeatablePrint;

    protected ?\Graphpinator\Field\ResolvableFieldSet $metaFields = null;

    public function __construct(?\Graphpinator\Utils\InterfaceSet $implements = null)
    {
        $this->implements = $implements
            ?? new \Graphpinator\Utils\InterfaceSet([]);
    }

    final public function createResolvedValue($rawValue) : \Graphpinator\Value\ResolvedValue
    {
        if ($rawValue === null) {
            return new \Graphpinator\Value\NullResolvedValue($this);
        }

        return new \Graphpinator\Value\TypeIntermediateValue($this, $rawValue);
    }

    final public function resolve(
        ?\Graphpinator\Normalizer\FieldSet $requestedFields,
        \Graphpinator\Value\ResolvedValue $parentResult
    ) : \Graphpinator\Value\TypeValue
    {
        if ($requestedFields === null) {
            throw new \Graphpinator\Exception\Resolver\SelectionOnComposite();
        }

        $resolved = new \stdClass();

        foreach ($requestedFields as $field) {
            if ($field->getTypeCondition() instanceof \Graphpinator\Type\Contract\NamedDefinition &&
                !$parentResult->getType()->isInstanceOf($field->getTypeCondition())) {
                continue;
            }

            foreach ($field->getDirectives() as $directive) {
                $directiveDef = $directive->getDirective();
                $arguments = new \Graphpinator\Resolver\ArgumentValueSet($directive->getArguments(), $directiveDef->getArguments());
                $directiveResult = $directiveDef->resolve($arguments);

                if ($directiveResult === \Graphpinator\Directive\DirectiveResult::SKIP) {
                    continue 2;
                }
            }

            $fieldDef = $this->getMetaFields()[$field->getName()]
                ?? $this->getFields()[$field->getName()];
            $resolved->{$field->getAlias()} = $fieldDef->resolve($parentResult, $field);
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

    private function getMetaFields() : \Graphpinator\Field\ResolvableFieldSet
    {
        if (!$this->metaFields instanceof \Graphpinator\Field\ResolvableFieldSet) {
            $this->metaFields = $this->getMetaFieldDefinition();
        }

        return $this->metaFields;
    }

    private function getMetaFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
    {
        return new \Graphpinator\Field\ResolvableFieldSet([
            new \Graphpinator\Field\ResolvableField(
                '__typename',
                \Graphpinator\Container\Container::String()->notNull(),
                function() {
                    return $this->getName();
                },
            ),
        ]);
    }
}
