<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\Operation;

final class Operation
{
    use \Nette\SmartObject;

    private string $type;
    private ?string $name;
    private ?\Graphpinator\Parser\Variable\VariableSet $variables;
    private ?\Graphpinator\Parser\Directive\DirectiveSet $directives;
    private \Graphpinator\Parser\Field\FieldSet $children;

    public function __construct(
        string $type,
        ?string $name,
        ?\Graphpinator\Parser\Variable\VariableSet $variables,
        ?\Graphpinator\Parser\Directive\DirectiveSet $directives,
        \Graphpinator\Parser\Field\FieldSet $children,
    )
    {
        $this->type = $type;
        $this->name = $name;
        $this->variables = $variables
            ?? new \Graphpinator\Parser\Variable\VariableSet();
        $this->directives = $directives
            ?? new \Graphpinator\Parser\Directive\DirectiveSet();
        $this->children = $children;
    }

    public function getType() : string
    {
        return $this->type;
    }

    public function getName() : ?string
    {
        return $this->name;
    }

    public function getFields() : \Graphpinator\Parser\Field\FieldSet
    {
        return $this->children;
    }

    public function getVariables() : \Graphpinator\Parser\Variable\VariableSet
    {
        return $this->variables;
    }

    public function getDirectives() : \Graphpinator\Parser\Directive\DirectiveSet
    {
        return $this->directives;
    }

    public function normalize(
        \Graphpinator\Type\Schema $schema,
        \Graphpinator\Parser\Fragment\FragmentSet $fragmentDefinitions,
    ) : \Graphpinator\Normalizer\Operation\Operation
    {
        $operationType = match ($this->type) {
            \Graphpinator\Tokenizer\OperationType::QUERY => $schema->getQuery(),
            \Graphpinator\Tokenizer\OperationType::MUTATION => $schema->getMutation(),
            \Graphpinator\Tokenizer\OperationType::SUBSCRIPTION => $schema->getSubscription(),
        };

        if (!$operationType instanceof \Graphpinator\Type\Type) {
            throw new \Graphpinator\Exception\Normalizer\OperationNotSupported();
        }

        $variables = $this->variables->normalize($schema->getContainer());
        $children = $this->children->normalize($operationType, $schema->getContainer(), $fragmentDefinitions, $variables);
        $directives = $this->directives->normalize($operationType, $schema->getContainer(), $variables);
        $args = [$operationType, $children, $variables, $directives, $this->getName()];

        return match ($this->type) {
            \Graphpinator\Tokenizer\OperationType::QUERY => new \Graphpinator\Normalizer\Operation\Query(...$args),
            \Graphpinator\Tokenizer\OperationType::MUTATION => new \Graphpinator\Normalizer\Operation\Mutation(...$args),
            \Graphpinator\Tokenizer\OperationType::SUBSCRIPTION => new \Graphpinator\Normalizer\Operation\Subscription(...$args),
        };
    }
}
