<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\Fragment;

final class Fragment
{
    use \Nette\SmartObject;

    private string $name;
    private \Graphpinator\Parser\FieldSet $fields;
    private \Graphpinator\Parser\TypeRef\NamedTypeRef $typeCond;

    public function __construct(string $name, \Graphpinator\Parser\FieldSet $fields, \Graphpinator\Parser\TypeRef\NamedTypeRef $typeCond)
    {
        $this->name = $name;
        $this->fields = $fields;
        $this->typeCond = $typeCond;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getFields() : \Graphpinator\Parser\FieldSet
    {
        return $this->fields;
    }
}
