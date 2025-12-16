<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\DirectiveUsage;

use Graphpinator\Common\Path;
use Graphpinator\Typesystem\Contract\Component;
use Graphpinator\Typesystem\Contract\ComponentVisitor;
use Graphpinator\Typesystem\Contract\TypeSystemDirective;
use Graphpinator\Typesystem\Exception\DirectiveUsageArgumentsInvalidMap;
use Graphpinator\Value\ArgumentValueSet;
use Graphpinator\Value\ConvertRawValueVisitor;

final class DirectiveUsage implements Component
{
    private ArgumentValueSet $argumentValues;

    /**
     * @param TypeSystemDirective $directive
     * @phpcs:ignore
     * @param array<string, mixed> $arguments
     */
    public function __construct(
        private TypeSystemDirective $directive,
        array $arguments,
    )
    {
        if (\count($arguments) > 0 && \array_is_list($arguments)) {
            throw new DirectiveUsageArgumentsInvalidMap();
        }

        $this->argumentValues = new ArgumentValueSet(
            (array) ConvertRawValueVisitor::convertArgumentSet(
                $directive->getArguments(),
                (object) $arguments,
                new Path(),
            ),
        );
    }

    public function getDirective() : TypeSystemDirective
    {
        return $this->directive;
    }

    public function getArgumentValues() : ArgumentValueSet
    {
        return $this->argumentValues;
    }

    #[\Override]
    public function accept(ComponentVisitor $visitor) : mixed
    {
        return $visitor->visitDirectiveUsage($this);
    }
}
