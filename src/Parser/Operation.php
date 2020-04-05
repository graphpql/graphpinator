<?php

declare(strict_types = 1);

namespace Graphpinator\Parser;

final class Operation
{
    use \Nette\SmartObject;

    private string $type;
    private ?string $name;
    private \Graphpinator\Parser\FieldSet $children;
    private \Graphpinator\Parser\Variable\VariableSet $variables;

    public function __construct(
        \Graphpinator\Parser\FieldSet $children,
        string $type = \Graphpinator\Tokenizer\OperationType::QUERY,
        ?string $name = null,
        ?\Graphpinator\Parser\Variable\VariableSet $variables = null
    ) {
        $this->children = $children;
        $this->type = $type;
        $this->name = $name;
        $this->variables = $variables ?? new \Graphpinator\Parser\Variable\VariableSet([]);
    }

    public function getType() : string
    {
        return $this->type;
    }

    public function getName() : ?string
    {
        return $this->name;
    }

    public function getFields() : \Graphpinator\Parser\FieldSet
    {
        return $this->children;
    }

    public function getVariables() : ?\Graphpinator\Parser\Variable\VariableSet
    {
        return $this->variables;
    }

    public function normalize(
        \Graphpinator\DI\TypeResolver $typeResolver,
        \Graphpinator\Parser\Fragment\FragmentSet $fragmentDefinitions,
        \Infinityloop\Utils\Json $variables
    ) : \Graphpinator\Request\Operation
    {
        $validatedVariables = $this->constructVariables($variables, $typeResolver);
        $schema = $typeResolver->getSchema();

        switch ($this->type) {
            case \Graphpinator\Tokenizer\OperationType::QUERY:
                $operation = $schema->getQuery();

                break;
            case \Graphpinator\Tokenizer\OperationType::MUTATION:
                $operation = $schema->getMutation();

                break;
            case \Graphpinator\Tokenizer\OperationType::SUBSCRIPTION:
                $operation = $schema->getSubscription();

                break;
            default:
                throw new \Exception('Unknown operation type');
        }

        return new \Graphpinator\Request\Operation(
            $operation,
            $this->children->normalize($typeResolver, $fragmentDefinitions, $validatedVariables)
        );
    }

    private function constructVariables(
        \Infinityloop\Utils\Json $values,
        \Graphpinator\DI\TypeResolver $typeResolver
    ) : \Graphpinator\Value\ValidatedValueSet
    {
        $return = [];

        foreach ($this->variables as $variable) {
            $return[$variable->getName()] = $variable->createValue($values, $typeResolver);
        }

        return new \Graphpinator\Value\ValidatedValueSet($return);
    }
}
