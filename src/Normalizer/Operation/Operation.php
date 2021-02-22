<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Operation;

final class Operation
{
    use \Nette\SmartObject;

    private \Graphpinator\Type\Type $operation;
    private \Graphpinator\Normalizer\Field\FieldSet $children;
    private \Graphpinator\Normalizer\Variable\VariableSet $variables;
    private ?string $name;

    public function __construct(
        \Graphpinator\Type\Type $operation,
        \Graphpinator\Normalizer\Field\FieldSet $children,
        \Graphpinator\Normalizer\Variable\VariableSet $variables,
        ?string $name
    )
    {
        $this->operation = $operation;
        $this->children = $children;
        $this->variables = $variables;
        $this->name = $name;
    }

    public function getType() : \Graphpinator\Type\Type
    {
        return $this->operation;
    }

    public function getFields() : \Graphpinator\Normalizer\Field\FieldSet
    {
        return $this->children;
    }

    public function getVariables() : \Graphpinator\Normalizer\Variable\VariableSet
    {
        return $this->variables;
    }

    public function getName() : ?string
    {
        return $this->name;
    }

    public function resolve() : \Graphpinator\Result
    {
        $resolver = new \Graphpinator\Resolver\ResolveVisitor(
            $this->children,
            new \Graphpinator\Value\TypeIntermediateValue($this->operation, null),
        );

        $data = $this->operation->accept($resolver);

        return new \Graphpinator\Result($data);
    }
}
