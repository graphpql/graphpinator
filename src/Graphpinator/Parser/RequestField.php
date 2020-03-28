<?php

declare(strict_types = 1);

namespace Infinityloop\Graphpinator\Parser;

final class RequestField
{
    use \Nette\SmartObject;

    private string $name;
    private ?RequestFieldSet $children;
    private ?\Infinityloop\Graphpinator\Type\Contract\NamedDefinition $conditionType;
    private \Infinityloop\Graphpinator\Value\GivenValueSet $arguments;

    public function __construct(
        string $name,
        ?RequestFieldSet $children = null,
        ?\Infinityloop\Graphpinator\Type\Contract\NamedDefinition $conditionType = null,
        ?\Infinityloop\Graphpinator\Value\GivenValueSet $arguments = null
    ) {
        $this->name = $name;
        $this->children = $children;
        $this->conditionType = $conditionType;
        $this->arguments = $arguments instanceof \Infinityloop\Graphpinator\Value\GivenValueSet
            ? $arguments
            : new \Infinityloop\Graphpinator\Value\GivenValueSet([]);
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getChildren() : ?RequestFieldSet
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
