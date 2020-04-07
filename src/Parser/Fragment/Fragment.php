<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\Fragment;

final class Fragment
{
    use \Nette\SmartObject;

    private string $name;
    private \Graphpinator\Parser\TypeRef\NamedTypeRef $typeCond;
    private \Graphpinator\Parser\FieldSet $fields;

    public function __construct(string $name, \Graphpinator\Parser\TypeRef\NamedTypeRef $typeCond, \Graphpinator\Parser\FieldSet $fields)
    {
        $this->name = $name;
        $this->typeCond = $typeCond;
        $this->fields = $fields;

        foreach ($this->fields as $field) {
            $field->setTypeCondition($typeCond);
        }
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getFields() : \Graphpinator\Parser\FieldSet
    {
        return $this->fields;
    }

    public function getTypeCond() : \Graphpinator\Parser\TypeRef\NamedTypeRef
    {
        return $this->typeCond;
    }
}
