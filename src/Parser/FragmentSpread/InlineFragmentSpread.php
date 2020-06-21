<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\FragmentSpread;

final class InlineFragmentSpread implements \Graphpinator\Parser\FragmentSpread\FragmentSpread
{
    use \Nette\SmartObject;

    private \Graphpinator\Parser\FieldSet $fields;
    private \Graphpinator\Parser\Directive\DirectiveSet $directives;
    private ?\Graphpinator\Parser\TypeRef\NamedTypeRef $typeCond;

    public function __construct(
        \Graphpinator\Parser\FieldSet $fields,
        ?\Graphpinator\Parser\Directive\DirectiveSet $directives = null,
        ?\Graphpinator\Parser\TypeRef\NamedTypeRef $typeCond = null
    )
    {
        $this->fields = $fields;
        $this->directives = $directives
            ?? new \Graphpinator\Parser\Directive\DirectiveSet([], \Graphpinator\Directive\DirectiveLocation::INLINE_FRAGMENT);
        $this->typeCond = $typeCond;
    }

    public function getFields() : \Graphpinator\Parser\FieldSet
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

    public function normalize(\Graphpinator\Type\Container\Container $typeContainer, \Graphpinator\Parser\Fragment\FragmentSet $fragmentDefinitions) : \Graphpinator\Normalizer\FragmentSpread\FragmentSpread
    {
        return new \Graphpinator\Normalizer\FragmentSpread\FragmentSpread(
            $this->fields->normalize($typeContainer, $fragmentDefinitions),
            $this->directives->normalize($typeContainer),
            $this->typeCond instanceof \Graphpinator\Parser\TypeRef\NamedTypeRef
                ? $this->typeCond->normalize($typeContainer)
                : null,
        );
    }
}
