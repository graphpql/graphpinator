<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Operation;

abstract class Operation
{
    use \Nette\SmartObject;

    protected \Graphpinator\Type\Type $operation;
    protected \Graphpinator\Normalizer\Field\FieldSet $children;
    protected \Graphpinator\Normalizer\Variable\VariableSet $variables;
    protected \Graphpinator\Normalizer\Directive\DirectiveSet $directives;
    protected ?string $name;

    public function __construct(
        \Graphpinator\Type\Type $operation,
        \Graphpinator\Normalizer\Field\FieldSet $children,
        \Graphpinator\Normalizer\Variable\VariableSet $variables,
        \Graphpinator\Normalizer\Directive\DirectiveSet $directives,
        ?string $name
    )
    {
        $this->operation = $operation;
        $this->children = $children;
        $this->variables = $variables;
        $this->directives = $directives;
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

    public function getDirectives() : \Graphpinator\Normalizer\Directive\DirectiveSet
    {
        return $this->directives;
    }

    public function getName() : ?string
    {
        return $this->name;
    }

    abstract public function resolve() : \Graphpinator\Result;
}
