<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\TypeRef;

final class NotNullRef implements TypeRef
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

    public function normalize(\Graphpinator\Type\Container\Container $typeContainer) : \Graphpinator\Type\NotNullType
    {
        return new \Graphpinator\Type\NotNullType($this->innerRef->normalize($typeContainer));
    }
}
