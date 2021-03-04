<?php

declare(strict_types = 1);

namespace Graphpinator\DirectiveUsage;

final class DirectiveUsage implements \Graphpinator\Typesystem\Component
{
    use \Nette\SmartObject;

    private \Graphpinator\Directive\Contract\TypeSystemDefinition $directive;
    private \Graphpinator\Value\ArgumentValueSet $argumentValues;

    public function __construct(
        \Graphpinator\Directive\Contract\TypeSystemDefinition $directive,
        array $arguments,
    )
    {
        $this->directive = $directive;
        $this->argumentValues = \Graphpinator\Value\ArgumentValueSet::fromRaw($arguments, $directive->getArguments());
    }

    public function getDirective() : \Graphpinator\Directive\Contract\TypeSystemDefinition
    {
        return $this->directive;
    }

    public function getArgumentValues() : \Graphpinator\Value\ArgumentValueSet
    {
        return $this->argumentValues;
    }

    public function accept(\Graphpinator\Typesystem\ComponentVisitor $visitor) : mixed
    {
        return $visitor->visitDirectiveUsage($this);
    }
}
