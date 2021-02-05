<?php

declare(strict_types = 1);

namespace Graphpinator\Directive;

final class DirectiveUsage
{
    use \Nette\SmartObject;

    private \Graphpinator\Directive\Contract\TypeSystemDefinition $directive;
    private array $rawArguments;
    private ?\Graphpinator\Value\ArgumentValueSet $arguments = null;

    public function __construct(
        \Graphpinator\Directive\Contract\TypeSystemDefinition $directive,
        array $arguments,
    )
    {
        $this->directive = $directive;
        $this->rawArguments = $arguments;
    }

    public function getDirective() : \Graphpinator\Directive\Contract\TypeSystemDefinition
    {
        return $this->directive;
    }

    public function getArguments() : \Graphpinator\Value\ArgumentValueSet
    {
        if ($this->arguments === null) {
            $this->arguments = \Graphpinator\Value\ArgumentValueSet::fromRaw($this->rawArguments, $this->directive);
        }

        return $this->arguments;
    }

    public function print() : string
    {
        $return = '@' . $this->directive->getName();
        $notNullArguments = [];

        foreach ($this->arguments as $argument) {
            if ($argument->getValue() instanceof \Graphpinator\Value\NullValue) {
                continue;
            }

            $notNullArguments[] = $argument->getArgument()->getName() . ': ' . $argument->getValue()->printValue();
        }

        if (\count($notNullArguments)) {
            $return .= '(' . \implode(', ', $notNullArguments) . ')';
        }

        return $return;
    }
}
