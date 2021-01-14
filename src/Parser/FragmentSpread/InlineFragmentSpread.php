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
            ?? new \Graphpinator\Parser\Directive\DirectiveSet([], \Graphpinator\Directive\ExecutableDirectiveLocation::INLINE_FRAGMENT);
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

    public function normalize(
        \Graphpinator\Type\Contract\NamedDefinition $parentType,
        \Graphpinator\Container\Container $typeContainer,
        \Graphpinator\Parser\Fragment\FragmentSet $fragmentDefinitions,
        \Graphpinator\Normalizer\Variable\VariableSet $variableSet,
    ) : \Graphpinator\Normalizer\FragmentSpread\FragmentSpread
    {
        $typeCond = $this->typeCond instanceof \Graphpinator\Parser\TypeRef\NamedTypeRef
            ? $this->typeCond->normalize($typeContainer)
            : null;

        if ($typeCond instanceof \Graphpinator\Type\Contract\NamedDefinition &&
            !$typeCond instanceof \Graphpinator\Type\Contract\TypeConditionable) {
            throw new \Graphpinator\Exception\Normalizer\TypeConditionOutputable();
        }

        $scopeType = $typeCond
            ?? $parentType;

        return new \Graphpinator\Normalizer\FragmentSpread\FragmentSpread(
            $this->fields->normalize($scopeType, $typeContainer, $fragmentDefinitions, $variableSet),
            $this->directives->normalize($scopeType, $typeContainer, $variableSet),
            $typeCond,
        );
    }
}
