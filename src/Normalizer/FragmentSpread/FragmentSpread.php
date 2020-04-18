<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\FragmentSpread;

final class FragmentSpread
{
    use \Nette\SmartObject;

    private \Graphpinator\Normalizer\FieldSet $children;
    private \Graphpinator\Normalizer\Directive\DirectiveSet $directives;
    private ?\Graphpinator\Type\Contract\NamedDefinition $typeCond;

    public function __construct(
        \Graphpinator\Normalizer\FieldSet $fields,
        \Graphpinator\Normalizer\Directive\DirectiveSet $directives,
        ?\Graphpinator\Type\Contract\NamedDefinition $typeCond
    )
    {
        $this->children = $fields;
        $this->directives = $directives;
        $this->typeCond = $typeCond;
    }

    public function getFields() : \Graphpinator\Normalizer\FieldSet
    {
        return $this->children;
    }

    public function getDirectives() : \Graphpinator\Normalizer\Directive\DirectiveSet
    {
        return $this->directives;
    }

    public function getTypeCondition() : ?\Graphpinator\Type\Contract\NamedDefinition
    {
        return $this->typeCond;
    }

    public function applyVariables(\Graphpinator\Resolver\VariableValueSet $variables) : self
    {
        return new self(
            $this->children->applyVariables($variables),
            $this->directives->applyVariables($variables),
            $this->typeCond,
        );
    }
}
