<?php

declare(strict_types = 1);

namespace Graphpinator\Request;

final class Field
{
    use \Nette\SmartObject;

    private string $name;
    private string $alias;
    private ?FieldSet $children;
    private \Graphpinator\Value\GivenValueSet $arguments;
    private ?\Graphpinator\Type\Contract\NamedDefinition $conditionType;

    public function __construct(
        string $name,
        ?string $alias = null,
        ?FieldSet $children = null,
        ?\Graphpinator\Value\GivenValueSet $arguments = null,
        ?\Graphpinator\Type\Contract\NamedDefinition $conditionType = null
    ) {
        $this->name = $name;
        $this->alias = $alias ?? $name;
        $this->children = $children;
        $this->arguments = $arguments instanceof \Graphpinator\Value\GivenValueSet
            ? $arguments
            : new \Graphpinator\Value\GivenValueSet([]);
        $this->conditionType = $conditionType;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getAlias() : string
    {
        return $this->alias;
    }

    public function getChildren() : ?FieldSet
    {
        return $this->children;
    }

    public function getConditionType() : ?\Graphpinator\Type\Contract\NamedDefinition
    {
        return $this->conditionType;
    }

    public function getArguments() : \Graphpinator\Value\GivenValueSet
    {
        return $this->arguments;
    }
}
