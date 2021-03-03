<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\Fragment;

final class Fragment
{
    use \Nette\SmartObject;

    public function __construct(
        private string $name,
        private \Graphpinator\Parser\TypeRef\NamedTypeRef $typeCond,
        private \Graphpinator\Parser\Directive\DirectiveSet $directives,
        private \Graphpinator\Parser\Field\FieldSet $fields,
    ) {}

    public function getName() : string
    {
        return $this->name;
    }

    public function getFields() : \Graphpinator\Parser\Field\FieldSet
    {
        return $this->fields;
    }

    public function getTypeCond() : \Graphpinator\Parser\TypeRef\NamedTypeRef
    {
        return $this->typeCond;
    }

    public function getDirectives() : \Graphpinator\Parser\Directive\DirectiveSet
    {
        return $this->directives;
    }
}
