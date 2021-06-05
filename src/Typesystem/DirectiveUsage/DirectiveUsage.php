<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\DirectiveUsage;

final class DirectiveUsage implements \Graphpinator\Typesystem\Contract\Component
{
    use \Nette\SmartObject;

    private \Graphpinator\Value\ArgumentValueSet $argumentValues;

    public function __construct(
        private \Graphpinator\Typesystem\Contract\TypeSystemDirective $directive,
        array $arguments,
    )
    {
        $this->argumentValues = new \Graphpinator\Value\ArgumentValueSet(
            (array) \Graphpinator\Value\ConvertRawValueVisitor::convertArgumentSet(
                $directive->getArguments(),
                (object) $arguments,
                new \Graphpinator\Common\Path(),
            ),
        );
    }

    public function getDirective() : \Graphpinator\Typesystem\Contract\TypeSystemDirective
    {
        return $this->directive;
    }

    public function getArgumentValues() : \Graphpinator\Value\ArgumentValueSet
    {
        return $this->argumentValues;
    }

    public function accept(\Graphpinator\Typesystem\Contract\ComponentVisitor $visitor) : mixed
    {
        return $visitor->visitDirectiveUsage($this);
    }
}
