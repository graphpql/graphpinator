<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Operation;

use Graphpinator\Enum\OperationType;
use Graphpinator\Normalizer\Directive\DirectiveSet;
use Graphpinator\Normalizer\Selection\SelectionSet;
use Graphpinator\Normalizer\Variable\VariableSet;
use Graphpinator\Typesystem\Type;

final class Operation
{
    public function __construct(
        private OperationType $type,
        private ?string $name,
        private Type $rootObject,
        private SelectionSet $children,
        private VariableSet $variables,
        private DirectiveSet $directives,
    )
    {
    }

    public function getType() : OperationType
    {
        return $this->type;
    }

    public function getRootObject() : Type
    {
        return $this->rootObject;
    }

    public function getSelections() : SelectionSet
    {
        return $this->children;
    }

    public function getVariables() : VariableSet
    {
        return $this->variables;
    }

    public function getDirectives() : DirectiveSet
    {
        return $this->directives;
    }

    public function getName() : ?string
    {
        return $this->name;
    }
}
