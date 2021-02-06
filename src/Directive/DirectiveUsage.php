<?php

declare(strict_types = 1);

namespace Graphpinator\Directive;

final class DirectiveUsage
{
    use \Nette\SmartObject;

    private \Graphpinator\Directive\Contract\TypeSystemDefinition $directive;
    private ?\Graphpinator\Type\Contract\Definition $type;
    private array $rawArguments;
    private ?\Graphpinator\Value\ArgumentValueSet $arguments = null;

    public function __construct(
        \Graphpinator\Directive\Contract\TypeSystemDefinition $directive,
        ?\Graphpinator\Type\Contract\Definition $type,
        array $arguments,
    )
    {
        $this->directive = $directive;
        $this->type = $type;
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

            if (!$this->directive->validateType($this->type, $this->arguments)) {
                throw new \Graphpinator\Exception\Constraint\InvalidConstraintType();
            }
        }

        return $this->arguments;
    }

    public function printSchema() : string
    {
        $return = '@' . $this->directive->getName();
        $notNullArguments = [];

        foreach ($this->getArguments() as $argument) {
            // do not print default value
            if ($argument->getValue()->getRawValue() === $argument->getArgument()->getDefaultValue()?->getRawValue()) {
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
