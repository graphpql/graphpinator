<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\DirectiveUsage;

use \Graphpinator\Typesystem\Contract\ComponentVisitor;
use \Graphpinator\Typesystem\Contract\TypeSystemDirective;
use \Graphpinator\Typesystem\Exception\DirectiveUsageArgumentsInvalidMap;
use \Graphpinator\Value\ArgumentValueSet;

final class DirectiveUsage implements \Graphpinator\Typesystem\Contract\Component
{
    use \Nette\SmartObject;

    private ArgumentValueSet $argumentValues;

    public function __construct(
        private TypeSystemDirective $directive,
        array $arguments,
    )
    {
        // replace with \array_is_list() for PHP 8.1
        if (\count($arguments) > 0 && \array_key_first($arguments) === 0) {
            throw new DirectiveUsageArgumentsInvalidMap();
        }

        $this->argumentValues = new \Graphpinator\Value\ArgumentValueSet(
            (array) \Graphpinator\Value\ConvertRawValueVisitor::convertArgumentSet(
                $directive->getArguments(),
                (object) $arguments,
                new \Graphpinator\Common\Path(),
            ),
        );
    }

    public function getDirective() : TypeSystemDirective
    {
        return $this->directive;
    }

    public function getArgumentValues() : \Graphpinator\Value\ArgumentValueSet
    {
        return $this->argumentValues;
    }

    public function accept(ComponentVisitor $visitor) : mixed
    {
        return $visitor->visitDirectiveUsage($this);
    }
}
