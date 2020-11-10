<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\FragmentSpread;

final class FragmentSpread
{
    use \Nette\SmartObject;

    private \Graphpinator\Normalizer\FieldSet $children;

    public function __construct(
        \Graphpinator\Normalizer\FieldSet $fields,
        \Graphpinator\Normalizer\Directive\DirectiveSet $directives,
        ?\Graphpinator\Type\Contract\TypeConditionable $typeCond
    )
    {
        $this->children = $fields;

        foreach ($this->children as $field) {
            $field->getDirectives()->merge($directives);
            $field->applyFragmentTypeCondition($typeCond);
        }
    }

    public function getFields() : \Graphpinator\Normalizer\FieldSet
    {
        return $this->children;
    }
}
