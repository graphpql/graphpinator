<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\FragmentSpread;

final class TypeFragmentSpread implements FragmentSpread
{
    use \Nette\SmartObject;

    private \Graphpinator\Parser\FieldSet $fields;

    public function __construct(\Graphpinator\Parser\TypeRef\NamedTypeRef $typeCond, \Graphpinator\Parser\FieldSet $fields)
    {
        $this->fields = $fields;

        foreach ($this->fields as $field) {
            $field->setTypeCondition($typeCond);
        }
    }

    public function getFields(\Graphpinator\Parser\Fragment\FragmentSet $fragmentDefinitions) : \Graphpinator\Parser\FieldSet
    {
        return $this->fields;
    }
}
