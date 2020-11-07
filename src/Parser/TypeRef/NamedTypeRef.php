<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\TypeRef;

final class NamedTypeRef implements \Graphpinator\Parser\TypeRef\TypeRef
{
    use \Nette\SmartObject;

    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function normalize(\Graphpinator\Container\Container $typeContainer) : \Graphpinator\Type\Contract\NamedDefinition
    {
        return $typeContainer->getType($this->name);
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function print() : string
    {
        return $this->name;
    }
}
