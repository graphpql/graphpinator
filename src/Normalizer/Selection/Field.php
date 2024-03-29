<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Selection;

final class Field implements Selection
{
    public function __construct(
        private \Graphpinator\Typesystem\Field\Field $field,
        private string $outputName,
        private \Graphpinator\Value\ArgumentValueSet $arguments,
        private \Graphpinator\Normalizer\Directive\DirectiveSet $directives,
        private ?\Graphpinator\Normalizer\Selection\SelectionSet $children = null,
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

    public function getArguments() : \Graphpinator\Value\ArgumentValueSet
    {
        return $this->arguments;
    }

    public function getDirectives() : \Graphpinator\Normalizer\Directive\DirectiveSet
    {
        return $this->directives;
    }

    public function getSelections() : ?\Graphpinator\Normalizer\Selection\SelectionSet
    {
        return $this->children;
    }

    public function applyVariables(\Graphpinator\Normalizer\VariableValueSet $variables) : void
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
