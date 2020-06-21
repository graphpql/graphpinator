<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\TypeRef;

final class ListTypeRef implements \Graphpinator\Parser\TypeRef\TypeRef
{
    use \Nette\SmartObject;

    private TypeRef $innerRef;

    public function __construct(TypeRef $innerRef)
    {
        $this->innerRef = $innerRef;
    }

    public function getInnerRef() : TypeRef
    {
        return $this->innerRef;
    }

    public function normalize(\Graphpinator\Type\Container\Container $typeContainer) : \Graphpinator\Type\ListType
    {
        return new \Graphpinator\Type\ListType($this->innerRef->normalize($typeContainer));
    }
}
