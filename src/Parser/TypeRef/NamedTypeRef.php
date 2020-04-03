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

    public function resolve(\Graphpinator\DI\TypeResolver $resolver): \Graphpinator\Type\Contract\NamedDefinition
    {
        return $resolver->getType($this->name);
    }
}
