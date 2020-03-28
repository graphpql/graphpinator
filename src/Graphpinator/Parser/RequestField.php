<?php

declare(strict_types = 1);

namespace PGQL\Parser;

final class RequestField
{
    use \Nette\SmartObject;

    private string $name;
    private ?RequestFieldSet $children;
    private ?\PGQL\Type\Contract\NamedDefinition $conditionType;
    private \PGQL\Value\GivenValueSet $arguments;

    public function __construct(
        string $name,
        ?RequestFieldSet $children = null,
        ?\PGQL\Type\Contract\NamedDefinition $conditionType = null,
        ?\PGQL\Value\GivenValueSet $arguments = null
    ) {
        $this->name = $name;
        $this->children = $children;
        $this->conditionType = $conditionType;
        $this->arguments = $arguments instanceof \PGQL\Value\GivenValueSet
            ? $arguments
            : new \PGQL\Value\GivenValueSet([]);
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getChildren() : ?RequestFieldSet
    {
        return $this->children;
    }

    public function getConditionType() : ?\PGQL\Type\Contract\NamedDefinition
    {
        return $this->conditionType;
    }

    public function getArguments() : \PGQL\Value\GivenValueSet
    {
        return $this->arguments;
    }
}
