<?php

declare(strict_types = 1);

namespace Graphpinator\Parser;

final class Operation
{
    use \Nette\SmartObject;

    private FieldSet $children;
    private string $type;
    private ?string $name;
    private ?array $variables;

    public function __construct(
        ?FieldSet $children = null,
        string $type = \Graphpinator\Tokenizer\OperationType::QUERY,
        ?string $name = null,
        ?array $variables = null
    ) {
        $this->children = $children;
        $this->type = $type;
        $this->name = $name;
        $this->variables = $variables;
    }

    public function normalize(
        \Graphpinator\DI\TypeResolver $typeResolver,
        array $fragmentDefinitions,
        \Infinityloop\Utils\Json $variableValues
    ) : \Graphpinator\Request\Operation
    {
        $validatedVariables = $this->constructVariables($variableValues, $typeResolver);
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
                throw new \Exception();
        }

        return new \Graphpinator\Request\Operation(
            $this->type,
            $this->name,
            $this->children->normalize($typeResolver, $operation, $fragmentDefinitions, $validatedVariables)
        );
    }

    private function constructVariables(
        \Infinityloop\Utils\Json $values,
        \Graphpinator\DI\TypeResolver $typeResolver
    ) : \Graphpinator\Value\ValidatedValueSet
    {
        $return = [];

        foreach ($this->variables as $variable) {
            \assert($variable instanceof Variable);

            $return[$variable->getName()] = $variable->createValue($values, $typeResolver);
        }

        return new \Graphpinator\Value\ValidatedValueSet($return);
    }
}
