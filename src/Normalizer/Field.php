<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer;

use Graphpinator\Resolver\VariableValueSet;

final class Field
{
    use \Nette\SmartObject;

    private string $name;
    private string $alias;
    private \Graphpinator\Parser\Value\NamedValueSet $arguments;
    private \Graphpinator\Normalizer\Directive\DirectiveSet $directives;
    private ?\Graphpinator\Normalizer\FieldSet $children;

    public function __construct(
        string $name,
        ?string $alias = null,
        ?\Graphpinator\Parser\Value\NamedValueSet $arguments = null,
        ?\Graphpinator\Normalizer\Directive\DirectiveSet $directives = null,
        ?\Graphpinator\Normalizer\FieldSet $children = null
    )
    {
        $this->name = $name;
        $this->alias = $alias ?? $name;
        $this->arguments = $arguments ?? new \Graphpinator\Parser\Value\NamedValueSet([]);
        $this->directives = $directives
            ?? new \Graphpinator\Normalizer\Directive\DirectiveSet([], \Graphpinator\Directive\DirectiveLocation::FIELD);
        $this->children = $children;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getAlias() : string
    {
        return $this->alias;
    }

    public function getArguments() : \Graphpinator\Parser\Value\NamedValueSet
    {
        return $this->arguments;
    }

    public function getDirectives() : \Graphpinator\Normalizer\Directive\DirectiveSet
    {
        return $this->directives;
    }

    public function getFields() : ?\Graphpinator\Normalizer\FieldSet
    {
        return $this->children;
    }

    public function applyVariables(VariableValueSet $variables) : self
    {
        return new self(
            $this->name,
            $this->alias,
            $this->arguments->applyVariables($variables),
            $this->directives->applyVariables($variables),
            $this->children instanceof FieldSet
                ? $this->children->applyVariables($variables)
                : null,
        );
    }
}
