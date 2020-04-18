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
        $this->variables = $variables
            ?? new \Graphpinator\Parser\Variable\VariableSet([]);
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

    public function getVariables() : \Graphpinator\Parser\Variable\VariableSet
    {
        return $this->variables;
    }

    public function normalize(
        \Graphpinator\Type\Schema $schema,
        \Graphpinator\Parser\Fragment\FragmentSet $fragmentDefinitions
    ) : \Graphpinator\Normalizer\Operation
    {
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
                throw new \Graphpinator\Exception\Parser\UnknownOperationType();
        }

        if (!$operation instanceof \Graphpinator\Type\Type) {
            throw new \Graphpinator\Exception\Normalizer\OperationNotSupported();
        }

        return new \Graphpinator\Normalizer\Operation(
            $operation,
            $this->children->normalize($schema->getContainer(), $fragmentDefinitions),
            $this->variables->normalize($schema->getContainer()),
        );
    }
}
