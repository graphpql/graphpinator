<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\TypeRef;

final class NamedTypeRef implements \Graphpinator\Parser\TypeRef\TypeRef
{
    use \Nette\SmartObject;

    public function __construct(
        private string $name,
    ) {}

    public function normalize(\Graphpinator\Container\Container $typeContainer) : \Graphpinator\Type\Contract\NamedDefinition
    {
        $type = $typeContainer->getType($this->name);

        if ($type instanceof \Graphpinator\Type\Contract\NamedDefinition) {
            return $type;
        }

        throw new \Graphpinator\Exception\Normalizer\UnknownType($this->name);
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
