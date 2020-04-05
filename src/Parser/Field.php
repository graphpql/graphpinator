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
    private ?\Graphpinator\Parser\TypeRef\NamedTypeRef $typeCond;

    public function __construct(
        string $name,
        ?string $alias,
        ?\Graphpinator\Parser\FieldSet $children,
        ?\Graphpinator\Parser\Value\NamedValueSet $arguments,
        ?\Graphpinator\Parser\TypeRef\NamedTypeRef $typeCond = null
    ) {
        $this->name = $name;
        $this->alias = $alias;
        $this->children = $children;
        $this->arguments = $arguments;
        $this->typeCond = $typeCond;
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

    public function getTypeCondition() : ?\Graphpinator\Parser\TypeRef\NamedTypeRef
    {
        return $this->typeCond;
    }

    public function setTypeCondition(\Graphpinator\Parser\TypeRef\NamedTypeRef $typeCond) : void
    {
        $this->typeCond = $typeCond;
    }

    public function normalize(
        \Graphpinator\DI\TypeResolver $typeResolver,
        \Graphpinator\Parser\Fragment\FragmentSet $fragmentDefinitions,
        \Graphpinator\Value\ValidatedValueSet $variables
    ) : \Graphpinator\Request\Field
    {
        return new \Graphpinator\Request\Field(
            $this->name,
            $this->alias,
            $this->arguments instanceof \Graphpinator\Parser\Value\NamedValueSet
                ? $this->arguments->normalize($variables)
                : null,
            $this->children instanceof \Graphpinator\Parser\FieldSet
                ? $this->children->normalize($typeResolver, $fragmentDefinitions, $variables)
                : null,
            $this->typeCond instanceof \Graphpinator\Parser\TypeRef\NamedTypeRef
                ? $this->typeCond->resolve($typeResolver)
                : null,
        );
    }
}
