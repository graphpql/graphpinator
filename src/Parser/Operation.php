<?php

declare(strict_types = 1);

namespace Graphpinator\Parser;

final class Operation
{
    use \Nette\SmartObject;

    private ?FieldSet $children;
    private string $operation;
    private ?string $name;
    private ?array $variables;

    public function __construct(
        ?FieldSet $children = null,
        string $operation = 'query',
        ?string $name = null,
        ?array $variables = null
    ) {
        $this->children = $children;
        $this->operation = $operation;
        $this->name = $name;
        $this->variables = $variables;
    }
}
