<?php

declare(strict_types = 1);

namespace Graphpinator\Type;

abstract class Type extends \Graphpinator\Type\Contract\ConcreteDefinition implements
    \Graphpinator\Type\Contract\Resolvable,
    \Graphpinator\Type\Contract\InterfaceImplementor
{
    use \Graphpinator\Type\Contract\TResolvable;
    use \Graphpinator\Type\Contract\TInterfaceImplementor;
    use \Graphpinator\Printable\TRepeatablePrint;

    protected ?\Graphpinator\Field\ResolvableFieldSet $metaFields = null;

    public function __construct(?\Graphpinator\Utils\InterfaceSet $implements = null)
    {
        $this->implements = $implements
            ?? new \Graphpinator\Utils\InterfaceSet([]);
    }

    final public function createValue($rawValue) : \Graphpinator\Resolver\Value\ValidatedValue
    {
        return \Graphpinator\Resolver\Value\TypeValue::create($rawValue, $this);
    }

    final public function isInstanceOf(\Graphpinator\Type\Contract\Definition $type) : bool
    {
        if ($type instanceof \Graphpinator\Type\Contract\AbstractDefinition) {
            return $type->isImplementedBy($this);
        }

        return parent::isInstanceOf($type);
    }

    final public function resolve(?\Graphpinator\Normalizer\FieldSet $requestedFields, \Graphpinator\Resolver\FieldResult $parentResult) : \stdClass
    {
        if ($requestedFields === null) {
            throw new \Graphpinator\Exception\Resolver\SelectionOnComposite();
        }

        $resolved = [];

        foreach ($requestedFields as $field) {
            if ($field->getTypeCondition() instanceof \Graphpinator\Type\Contract\NamedDefinition &&
                !$parentResult->getResult()->getType()->isInstanceOf($field->getTypeCondition())) {
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
            $arguments = new \Graphpinator\Resolver\ArgumentValueSet($field->getArguments(), $fieldDef->getArguments());
            $innerResult = $fieldDef->resolve($parentResult, $arguments);

            $resolved[$field->getAlias()] = $innerResult->getResult() instanceof \Graphpinator\Resolver\Value\NullValue
                ? $innerResult->getResult()
                : $innerResult->getResult()->getType()->resolve($field->getFields(), $innerResult);
        }

        return (object) $resolved;
    }

    final public function getMetaFields() : \Graphpinator\Field\ResolvableFieldSet
    {
        if (!$this->metaFields instanceof \Graphpinator\Field\ResolvableFieldSet) {
            $this->metaFields = $this->getMetaFieldDefinition();
        }

        return $this->metaFields;
    }

    final public function addMetaField(\Graphpinator\Field\ResolvableField $field) : void
    {
        $this->getMetaFields()->offsetSet($field->getName(), $field);
    }

    final public function getTypeKind() : string
    {
        return \Graphpinator\Type\Introspection\TypeKind::OBJECT;
    }

    final public function printSchema() : string
    {
        return $this->printDescription()
            . 'type ' . $this->getName() . $this->printImplements() . ' {' . \PHP_EOL
            . $this->printItems($this->getFields())
            . '}';
    }

    abstract protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet;

    private function getMetaFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
    {
        return new \Graphpinator\Field\ResolvableFieldSet([
            new \Graphpinator\Field\ResolvableField(
                '__typename',
                \Graphpinator\Type\Container\Container::String()->notNull(),
                function() {
                    return $this->getName();
                },
            ),
        ]);
    }
}
