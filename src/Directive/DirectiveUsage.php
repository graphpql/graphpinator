<?php

declare(strict_types = 1);

namespace Graphpinator\Directive;

final class DirectiveUsage
{
    use \Nette\SmartObject;

    private \Graphpinator\Directive\Contract\TypeSystemDefinition $directive;
    private ?\Graphpinator\Type\Contract\Definition $type;
    private array $rawArgumentValues;
    private ?\Graphpinator\Value\ArgumentValueSet $argumentValues = null;

    public function __construct(
        \Graphpinator\Directive\Contract\TypeSystemDefinition $directive,
        ?\Graphpinator\Type\Contract\Definition $type,
        array $arguments,
    )
    {
        $this->directive = $directive;
        $this->type = $type;
        $this->rawArgumentValues = $arguments;
    }

    public function getDirective() : \Graphpinator\Directive\Contract\TypeSystemDefinition
    {
        return $this->directive;
    }

    public function getArgumentValues() : \Graphpinator\Value\ArgumentValueSet
    {
        if ($this->argumentValues === null) {
            $this->argumentValues = \Graphpinator\Value\ArgumentValueSet::fromRaw($this->rawArgumentValues, $this->directive);

            if (!$this->directive->validateType($this->type, $this->argumentValues)) {
                throw new \Graphpinator\Exception\Constraint\InvalidConstraintType();
            }
        }

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
