<?php

declare(strict_types = 1);

namespace Graphpinator\Parser;

final class Field
{
    use \Nette\SmartObject;

    private string $name;
    private string $alias;
    private FieldSet $children;
    private \Graphpinator\Parser\Value\NamedValueSet $arguments;
    private ?\Graphpinator\Parser\TypeRef\NamedTypeRef $typeCond;

    public function __construct(
        string $name,
        ?string $alias,
        FieldSet $children,
        \Graphpinator\Parser\Value\NamedValueSet $arguments,
        ?\Graphpinator\Parser\TypeRef\NamedTypeRef $typeCond = null
    ) {
        $this->name = $name;
        $this->alias = $alias ?? $name;
        $this->children = $children;
        $this->arguments = $arguments;
        $this->typeCond = $typeCond;
    }

    public function normalize(
        \Graphpinator\DI\TypeResolver $typeResolver,
        \Graphpinator\Field\Field $field,
        array $fragmentDefinitions,
        \Graphpinator\Value\ValidatedValueSet $variables
    ) : \Graphpinator\Request\Field
    {
        return new \Graphpinator\Request\Field(
            $this->name,
            $this->alias,
            $this->children->normalize($typeResolver, $field->getType(), $fragmentDefinitions, $variables),
            $this->arguments->normalize($field->getArguments(), $variables),
            $this->typeCond->resolve($typeResolver)
        );
    }
}
