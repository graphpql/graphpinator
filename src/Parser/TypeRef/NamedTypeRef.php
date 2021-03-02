<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\TypeRef;

final class NamedTypeRef implements \Graphpinator\Parser\TypeRef\TypeRef
{
    use \Nette\SmartObject;

    public function __construct(
        private string $name,
    ) {}

    public function getName() : string
    {
        return $this->name;
    }

    public function print() : string
    {
        return $this->name;
    }
}
