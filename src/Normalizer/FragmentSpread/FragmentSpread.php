<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\FragmentSpread;

final class FragmentSpread
{
    use \Nette\SmartObject;

    private \Graphpinator\Normalizer\FieldSet $children;
    private \Graphpinator\Normalizer\Directive\DirectiveSet $directives;
    private ?\Graphpinator\Type\Contract\TypeConditionable $typeCond;

    public function __construct(
        \Graphpinator\Normalizer\FieldSet $fields,
        \Graphpinator\Normalizer\Directive\DirectiveSet $directives,
        ?\Graphpinator\Type\Contract\TypeConditionable $typeCond
    )
    {
        $this->children = $fields;
        $this->directives = $directives;
        $this->typeCond = $typeCond;

        foreach ($this->children as $field) {
            $field->getDirectives()->merge($directives);
            $field->applyFragmentTypeCondition($typeCond);
        }
    }

    public function getFields() : \Graphpinator\Normalizer\FieldSet
    {
        return $this->children;
    }

    public function getDirectives() : \Graphpinator\Normalizer\Directive\DirectiveSet
    {
        return $this->directives;
    }

    public function getTypeCondition() : ?\Graphpinator\Type\Contract\TypeConditionable
    {
        return $this->typeCond;
    }
}
