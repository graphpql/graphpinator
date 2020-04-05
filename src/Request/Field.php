<?php

declare(strict_types = 1);

namespace Graphpinator\Request;

final class Field
{
    use \Nette\SmartObject;

    private string $name;
    private string $alias;
    private \Graphpinator\Parser\Value\NamedValueSet $arguments;
    private ?\Graphpinator\Request\FieldSet $children;
    private ?\Graphpinator\Type\Contract\NamedDefinition $conditionType;

    public function __construct(
        string $name,
        ?string $alias = null,
        ?\Graphpinator\Parser\Value\NamedValueSet $arguments = null,
        ?\Graphpinator\Request\FieldSet $children = null,
        ?\Graphpinator\Type\Contract\NamedDefinition $conditionType = null
    ) {
        $this->name = $name;
        $this->alias = $alias ?? $name;
        $this->arguments = $arguments ?? new \Graphpinator\Parser\Value\NamedValueSet([]);
        $this->children = $children;
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

    public function getArguments() : \Graphpinator\Parser\Value\NamedValueSet
    {
        return $this->arguments;
    }

    public function getChildren() : ?\Graphpinator\Request\FieldSet
    {
        return $this->children;
    }

    public function getConditionType() : ?\Graphpinator\Type\Contract\NamedDefinition
    {
        return $this->conditionType;
    }
}
