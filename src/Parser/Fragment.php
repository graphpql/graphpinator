<?php

declare(strict_types = 1);

namespace Graphpinator\Parser;

final class Fragment
{
    use \Nette\SmartObject;

    private string $name;

    public function __construct(string $name, \Graphpinator\Parser\TypeRef\NamedTypeRef $typeCond, \Graphpinator\Parser\FieldSet $fields)
    {
        $this->name = $name;
    }

    public function getName() : string
    {
        return $this->name;
    }
}
