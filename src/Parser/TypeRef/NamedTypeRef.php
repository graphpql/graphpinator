<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\TypeRef;

final class NamedTypeRef implements TypeRef
{
    use \Nette\SmartObject;

    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function create(\Graphpinator\DI\TypeResolver $resolver): \Graphpinator\Type\Contract\Definition
    {
        return $resolver->getType($this->name);
    }
}
