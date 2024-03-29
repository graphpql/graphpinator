<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\DirectiveUsage;

use \Graphpinator\Value\ArgumentValueSet;

final class DirectiveUsage implements \Graphpinator\Typesystem\Contract\Component
{
    private ArgumentValueSet $argumentValues;

    public function __construct(
        private \Graphpinator\Typesystem\Contract\TypeSystemDirective $directive,
        array $arguments,
    )
    {
        if (\count($arguments) > 0 && \array_is_list($arguments)) {
            throw new \Graphpinator\Typesystem\Exception\DirectiveUsageArgumentsInvalidMap();
        }

        $this->argumentValues = new ArgumentValueSet(
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

    public function getArgumentValues() : ArgumentValueSet
    {
        return $this->argumentValues;
    }

    public function accept(\Graphpinator\Typesystem\Contract\ComponentVisitor $visitor) : mixed
    {
        return $visitor->visitDirectiveUsage($this);
    }
}
