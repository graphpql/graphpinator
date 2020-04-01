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

    public function create(\Graphpinator\DI\TypeResolver $resolver): \Graphpinator\Type\Contract\Definition
    {
        return new \Graphpinator\Type\NotNullType($this->innerRef->create($resolver));
    }
}
