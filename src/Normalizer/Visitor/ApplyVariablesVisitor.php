<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Visitor;

use Graphpinator\Normalizer\Selection\Field;
use Graphpinator\Normalizer\Selection\FragmentSpread;
use Graphpinator\Normalizer\Selection\InlineFragment;
use Graphpinator\Normalizer\Selection\SelectionVisitor;
use Graphpinator\Normalizer\VariableValueSet;

final readonly class ApplyVariablesVisitor implements SelectionVisitor
{
    public function __construct(
        private VariableValueSet $variables,
    )
    {
    }

    #[\Override]
    public function visitField(Field $field) : null
    {
        $field->arguments->applyVariables($this->variables);
        $field->directives->applyVariables($this->variables);
        $field->children?->applyVariables($this->variables);

        return null;
    }

    #[\Override]
    public function visitFragmentSpread(FragmentSpread $fragmentSpread) : null
    {
        $fragmentSpread->children->applyVariables($this->variables);

        return null;
    }

    #[\Override]
    public function visitInlineFragment(InlineFragment $inlineFragment) : null
    {
        $inlineFragment->children->applyVariables($this->variables);

        return null;
    }
}
