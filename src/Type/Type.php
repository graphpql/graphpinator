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

    public function createValue($rawValue) : \Graphpinator\Resolver\Value\ValidatedValue
    {
        return \Graphpinator\Resolver\Value\TypeValue::create($rawValue, $this);
    }

    public function isInstanceOf(\Graphpinator\Type\Contract\Definition $type) : bool
    {
        if ($type instanceof \Graphpinator\Type\Contract\AbstractDefinition) {
            return $type->isImplementedBy($this);
        }

        return parent::isInstanceOf($type);
    }

    public function resolve(?\Graphpinator\Normalizer\FieldSet $requestedFields, \Graphpinator\Resolver\FieldResult $parentResult) : array
    {
        if ($requestedFields === null) {
            throw new \Graphpinator\Exception\Resolver\SelectionOnComposite();
        }

        $resolved = [];
        $this->resolveFieldSet($resolved, $requestedFields, $parentResult);

        foreach ($requestedFields->getFragments() as $fragmentSpread) {
            foreach ($fragmentSpread->getDirectives() as $directive) {
                $directiveDef = $directive->getDirective();
                $arguments = new \Graphpinator\Resolver\ArgumentValueSet($directive->getArguments(), $directiveDef->getArguments());
                $directiveResult = $directiveDef->resolve($arguments);

                if ($directiveResult === \Graphpinator\Directive\DirectiveResult::SKIP) {
                    continue 2;
                }
            }

            if ($fragmentSpread->getTypeCondition() instanceof \Graphpinator\Type\Contract\NamedDefinition &&
                !$parentResult->getType()->isInstanceOf($fragmentSpread->getTypeCondition())) {
                continue;
            }

            $this->resolveFieldSet($resolved, $fragmentSpread->getFields(), $parentResult);
        }

        return $resolved;
    }

    public function getMetaFields() : \Graphpinator\Field\ResolvableFieldSet
    {
        if (!$this->metaFields instanceof \Graphpinator\Field\ResolvableFieldSet) {
            $this->metaFields = $this->getMetaFieldDefinition();
        }

        return $this->metaFields;
    }

    public function addMetaField(\Graphpinator\Field\ResolvableField $field) : void
    {
        $this->getMetaFields()->offsetSet($field->getName(), $field);
    }

    public function getTypeKind() : string
    {
        return \Graphpinator\Type\Introspection\TypeKind::OBJECT;
    }

    public function printSchema() : string
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
                function() { return $this->getName(); },
            ),
        ]);
    }

    private function resolveFieldSet(
        array& $result,
        \Graphpinator\Normalizer\FieldSet $fieldSet,
        \Graphpinator\Resolver\FieldResult $parentResult
    ) : void
    {
        foreach ($fieldSet as $field) {
            if (\array_key_exists($field->getAlias(), $result)) {
                throw new \Graphpinator\Exception\Resolver\DuplicateField();
            }

            foreach ($field->getDirectives() as $directive) {
                $directiveDef = $directive->getDirective();
                $arguments = new \Graphpinator\Resolver\ArgumentValueSet($directive->getArguments(), $directiveDef->getArguments());
                $directiveResult = $directiveDef->resolve($arguments);

                if ($directiveResult === \Graphpinator\Directive\DirectiveResult::SKIP) {
                    return;
                }
            }

            $fieldDef = $this->getMetaFields()[$field->getName()]
                ?? $this->getFields()[$field->getName()];
            $arguments = new \Graphpinator\Resolver\ArgumentValueSet($field->getArguments(), $fieldDef->getArguments());
            $innerResult = $fieldDef->resolve($parentResult, $arguments);

            $result[$field->getAlias()] = $innerResult->getResult() instanceof \Graphpinator\Resolver\Value\NullValue
                ? $innerResult->getResult()
                : $innerResult->getType()->resolve($field->getFields(), $innerResult);
        }
    }
}
