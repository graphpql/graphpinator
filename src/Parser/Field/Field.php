<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\Field;

final class Field
{
    use \Nette\SmartObject;

    private string $name;
    private ?string $alias;
    private ?\Graphpinator\Parser\Field\FieldSet $children;
    private ?\Graphpinator\Parser\Value\NamedValueSet $arguments;
    private ?\Graphpinator\Parser\Directive\DirectiveSet $directives;

    public function __construct(
        string $name,
        ?string $alias = null,
        ?\Graphpinator\Parser\Field\FieldSet $children = null,
        ?\Graphpinator\Parser\Value\NamedValueSet $arguments = null,
        ?\Graphpinator\Parser\Directive\DirectiveSet $directives = null
    )
    {
        $this->name = $name;
        $this->alias = $alias;
        $this->children = $children;
        $this->arguments = $arguments;
        $this->directives = $directives;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getAlias() : ?string
    {
        return $this->alias;
    }

    public function getFields() : ?\Graphpinator\Parser\Field\FieldSet
    {
        return $this->children;
    }

    public function getArguments() : ?\Graphpinator\Parser\Value\NamedValueSet
    {
        return $this->arguments;
    }

    public function getDirectives() : ?\Graphpinator\Parser\Directive\DirectiveSet
    {
        return $this->directives;
    }

    public function normalize(
        \Graphpinator\Type\Contract\NamedDefinition $parentType,
        \Graphpinator\Container\Container $typeContainer,
        \Graphpinator\Parser\Fragment\FragmentSet $fragmentDefinitions
    ) : \Graphpinator\Normalizer\Field\Field
    {
        return new \Graphpinator\Normalizer\Field\Field(
            $this,
            $parentType,
            $typeContainer,
            $fragmentDefinitions,
        );
    }
}
