<?php

declare(strict_types = 1);

namespace Graphpinator\Parser;

final class Field
{
    use \Nette\SmartObject;

    private string $name;
    private ?string $alias;
    private ?\Graphpinator\Parser\FieldSet $children;
    private ?\Graphpinator\Parser\Value\NamedValueSet $arguments;
    private ?\Graphpinator\Parser\Directive\DirectiveSet $directives;

    public function __construct(
        string $name,
        ?string $alias = null,
        ?\Graphpinator\Parser\FieldSet $children = null,
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

    public function getFields() : ?\Graphpinator\Parser\FieldSet
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
        \Graphpinator\Type\Container\Container $typeContainer,
        \Graphpinator\Parser\Fragment\FragmentSet $fragmentDefinitions
    ) : \Graphpinator\Normalizer\Field
    {
        return new \Graphpinator\Normalizer\Field(
            $this->name,
            $this->alias,
            $this->arguments,
            $this->directives instanceof \Graphpinator\Parser\Directive\DirectiveSet
                ? $this->directives->normalize($typeContainer)
                : null,
            $this->children instanceof \Graphpinator\Parser\FieldSet
                ? $this->children->normalize($typeContainer, $fragmentDefinitions)
                : null,
        );
    }
}
