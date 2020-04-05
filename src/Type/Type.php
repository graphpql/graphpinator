<?php

declare(strict_types = 1);

namespace Graphpinator\Type;

abstract class Type extends \Graphpinator\Type\Contract\ConcreteDefinition implements
    \Graphpinator\Type\Contract\Resolvable,
    \Graphpinator\Type\Utils\InterfaceImplementor
{
    use \Graphpinator\Type\Contract\TResolvable;
    use \Graphpinator\Type\Utils\TInterfaceImplementor;

    protected \Graphpinator\Field\ResolvableFieldSet $fields;

    public function __construct(\Graphpinator\Field\ResolvableFieldSet $fields, ?\Graphpinator\Type\Utils\InterfaceSet $implements = null)
    {
        $this->fields = $fields;
        $this->implements = $implements ?? new \Graphpinator\Type\Utils\InterfaceSet([]);

        $this->validateInterfaces();
    }

    public function createValue($rawValue) : \Graphpinator\Value\ValidatedValue
    {
        return \Graphpinator\Value\TypeValue::create($rawValue, $this);
    }

    public function isInstanceOf(\Graphpinator\Type\Contract\Definition $type) : bool
    {
        if ($type instanceof \Graphpinator\Type\Contract\AbstractDefinition) {
            return $type->isImplementedBy($this);
        }

        return parent::isInstanceOf($type);
    }

    public function getFields() : \Graphpinator\Field\ResolvableFieldSet
    {
        return $this->fields;
    }

    public function resolveFields(?\Graphpinator\Request\FieldSet $requestedFields, \Graphpinator\Field\ResolveResult $result) : array
    {
        if ($requestedFields === null) {
            throw new \Exception('Composite type without fields specified.');
        }

        $resolved = [];

        foreach ($requestedFields as $request) {
            if ($request->getConditionType() instanceof \Graphpinator\Type\Contract\NamedDefinition &&
                !$result->getType()->isInstanceOf($request->getConditionType())) {
                continue;
            }

            $field = $this->fields[$request->getName()];
            $arguments = new \Graphpinator\Value\ArgumentValueSet($request->getArguments(), $field->getArguments());
            $innerResult = $field->resolve($result, $arguments);

            $resolved[$request->getAlias()] = $innerResult->getType()->resolveFields($request->getChildren(), $innerResult);
        }

        return $resolved;
    }
}
