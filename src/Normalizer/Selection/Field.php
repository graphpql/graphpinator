<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Selection;

use Graphpinator\Normalizer\Directive\DirectiveSet;
use Graphpinator\Normalizer\VariableValueSet;
use Graphpinator\Value\ArgumentValueSet;

final class Field implements Selection
{
    public function __construct(
        private \Graphpinator\Typesystem\Field\Field $field,
        private string $outputName,
        private ArgumentValueSet $arguments,
        private DirectiveSet $directives,
        private ?SelectionSet $children = null,
    )
    {
    }

    public function getField() : \Graphpinator\Typesystem\Field\Field
    {
        return $this->field;
    }

    public function getName() : string
    {
        return $this->field->getName();
    }

    public function getOutputName() : string
    {
        return $this->outputName;
    }

    public function getArguments() : ArgumentValueSet
    {
        return $this->arguments;
    }

    public function getDirectives() : DirectiveSet
    {
        return $this->directives;
    }

    public function getSelections() : ?SelectionSet
    {
        return $this->children;
    }

    public function applyVariables(VariableValueSet $variables) : void
    {
        $this->arguments->applyVariables($variables);
        $this->directives->applyVariables($variables);
        $this->children?->applyVariables($variables);
    }

    public function accept(SelectionVisitor $visitor) : mixed
    {
        return $visitor->visitField($this);
    }
}
