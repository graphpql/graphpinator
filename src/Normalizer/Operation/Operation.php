<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Operation;

final class Operation
{
    use \Nette\SmartObject;

    public function __construct(
        private string $type,
        private ?string $name,
        private \Graphpinator\Typesystem\Type $rootObject,
        private \Graphpinator\Normalizer\Selection\SelectionSet $children,
        private \Graphpinator\Normalizer\Variable\VariableSet $variables,
        private \Graphpinator\Normalizer\Directive\DirectiveSet $directives,
    )
    {
    }

    public function getType() : string
    {
        return $this->type;
    }

    public function getRootObject() : \Graphpinator\Typesystem\Type
    {
        return $this->rootObject;
    }

    public function getSelections() : \Graphpinator\Normalizer\Selection\SelectionSet
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
}
