<?php

declare(strict_types = 1);

namespace Infinityloop\Graphpinator\Request;

final class Field
{
    use \Nette\SmartObject;

    private string $name;
    private string $alias;
    private ?FieldSet $children;
    private \Infinityloop\Graphpinator\Value\GivenValueSet $arguments;
    private ?\Infinityloop\Graphpinator\Type\Contract\NamedDefinition $conditionType;

    public function __construct(
        string $name,
        ?string $alias = null,
        ?FieldSet $children = null,
        ?\Infinityloop\Graphpinator\Value\GivenValueSet $arguments = null,
        ?\Infinityloop\Graphpinator\Type\Contract\NamedDefinition $conditionType = null
    ) {
        $this->name = $name;
        $this->alias = $alias ?? $name;
        $this->children = $children;
        $this->arguments = $arguments instanceof \Infinityloop\Graphpinator\Value\GivenValueSet
            ? $arguments
            : new \Infinityloop\Graphpinator\Value\GivenValueSet([]);
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

    public function getConditionType() : ?\Infinityloop\Graphpinator\Type\Contract\NamedDefinition
    {
        return $this->conditionType;
    }

    public function getArguments() : \Infinityloop\Graphpinator\Value\GivenValueSet
    {
        return $this->arguments;
    }
}
