<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\FragmentSpread;

final class InlineFragmentSpread implements \Graphpinator\Parser\FragmentSpread\FragmentSpread
{
    use \Nette\SmartObject;

    private \Graphpinator\Parser\Field\FieldSet $fields;
    private \Graphpinator\Parser\Directive\DirectiveSet $directives;
    private ?\Graphpinator\Parser\TypeRef\NamedTypeRef $typeCond;

    public function __construct(
        \Graphpinator\Parser\Field\FieldSet $fields,
        ?\Graphpinator\Parser\Directive\DirectiveSet $directives = null,
        ?\Graphpinator\Parser\TypeRef\NamedTypeRef $typeCond = null,
    )
    {
        $this->fields = $fields;
        $this->directives = $directives
            ?? new \Graphpinator\Parser\Directive\DirectiveSet();
        $this->typeCond = $typeCond;
    }

    public function getFields() : \Graphpinator\Parser\Field\FieldSet
    {
        return $this->fields;
    }

    public function getDirectives() : \Graphpinator\Parser\Directive\DirectiveSet
    {
        return $this->directives;
    }

    public function getTypeCond() : ?\Graphpinator\Parser\TypeRef\NamedTypeRef
    {
        return $this->typeCond;
    }
}
