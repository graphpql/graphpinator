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

    public function resolve(\Graphpinator\Type\Container\Container $typeContainer): \Graphpinator\Type\Contract\NamedDefinition
    {
        return $typeContainer->getType($this->name);
    }

    public function getName() : string
    {
        return $this->name;
    }
}
