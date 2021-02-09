<?php

declare(strict_types = 1);

namespace Graphpinator\Directive;

final class DirectiveUsage
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
        $this->argumentValues = \Graphpinator\Value\ArgumentValueSet::fromRaw($arguments, $this->directive);
    }

    public function getDirective() : \Graphpinator\Directive\Contract\TypeSystemDefinition
    {
        return $this->directive;
    }

    public function getArgumentValues() : \Graphpinator\Value\ArgumentValueSet
    {
        return $this->argumentValues;
    }

    public function printSchema() : string
    {
        $return = '@' . $this->directive->getName();
        $printableArguments = [];

        foreach ($this->getArgumentValues() as $argument) {
            // do not print default value
            if ($argument->getValue()->getRawValue() === $argument->getArgument()->getDefaultValue()?->getRawValue()) {
                continue;
            }

            $printableArguments[] = $argument->getArgument()->getName() . ': ' . $argument->getValue()->printValue();
        }

        if (\count($printableArguments)) {
            $return .= '(' . \implode(', ', $printableArguments) . ')';
        }

        return $return;
    }
}
