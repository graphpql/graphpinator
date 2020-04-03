<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\TypeRef;

final class ListTypeRef implements TypeRef
{
    use \Nette\SmartObject;

    private TypeRef $innerRef;

    public function __construct(TypeRef $innerRef)
    {
        $this->innerRef = $innerRef;
    }

    public function resolve(\Graphpinator\DI\TypeResolver $resolver) : \Graphpinator\Type\ListType
    {
        return new \Graphpinator\Type\ListType($this->innerRef->resolve($resolver));
    }
}
