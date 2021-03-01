<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\FragmentSpread;

final class NamedFragmentSpread implements \Graphpinator\Parser\FragmentSpread\FragmentSpread
{
    use \Nette\SmartObject;

    private string $name;
    private \Graphpinator\Parser\Directive\DirectiveSet $directives;

    public function __construct(
        string $name,
        ?\Graphpinator\Parser\Directive\DirectiveSet $directives = null,
    )
    {
        $this->name = $name;
        $this->directives = $directives
            ?? new \Graphpinator\Parser\Directive\DirectiveSet();
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getDirectives() : \Graphpinator\Parser\Directive\DirectiveSet
    {
        return $this->directives;
    }

    public function normalize(
        \Graphpinator\Type\Contract\NamedDefinition $parentType,
        \Graphpinator\Container\Container $typeContainer,
        \Graphpinator\Parser\Fragment\FragmentSet $fragmentDefinitions,
        \Graphpinator\Normalizer\Variable\VariableSet $variableSet,
    ) : \Graphpinator\Normalizer\FragmentSpread\FragmentSpread
    {
        if (!$fragmentDefinitions->offsetExists($this->name)) {
            throw new \Graphpinator\Exception\Normalizer\UnknownFragment($this->name);
        }

        $fragment = $fragmentDefinitions->offsetGet($this->name);
        $typeCond = $fragment->getTypeCond()->normalize($typeContainer);

        if (!$typeCond instanceof \Graphpinator\Type\Contract\TypeConditionable) {
            throw new \Graphpinator\Exception\Normalizer\TypeConditionOutputable();
        }

        $fields = $fragment->getFields()->normalize($typeCond, $typeContainer, $fragmentDefinitions, $variableSet);

        foreach ($fields as $field) {
            $directives = new \Graphpinator\Normalizer\Directive\DirectiveSet(
                $this->directives, $field->getField(), $typeContainer, $variableSet
            );

            $field->getDirectives()->merge($directives);
            $field->applyFragmentTypeCondition($typeCond);
        }

        return new \Graphpinator\Normalizer\FragmentSpread\FragmentSpread($fields);
    }
}
