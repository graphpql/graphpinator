<?php

declare(strict_types = 1);

namespace Graphpinator\Type;

abstract class InputType extends \Graphpinator\Type\Contract\ConcreteDefinition implements
    \Graphpinator\Type\Contract\Inputable
{
    use \Graphpinator\Directive\THasDirectives;

    protected const DATA_CLASS = \stdClass::class;

    protected ?\Graphpinator\Argument\ArgumentSet $arguments = null;
    private bool $cycleValidated = false;

    public function __construct()
    {
        $this->directiveUsages = new \Graphpinator\Directive\DirectiveUsageSet();
    }

    final public function createInputedValue(mixed $rawValue) : \Graphpinator\Value\InputedValue
    {
        if ($rawValue instanceof \stdClass) {
            return \Graphpinator\Value\InputValue::fromRaw($this, $rawValue);
        }

        if ($rawValue === null) {
            return new \Graphpinator\Value\NullInputedValue($this);
        }

        throw new \Graphpinator\Exception\Value\InvalidValue($this->getName(), $rawValue, true);
    }

    final public function getArguments() : \Graphpinator\Argument\ArgumentSet
    {
        if (!$this->arguments instanceof \Graphpinator\Argument\ArgumentSet) {
            $this->arguments = $this->getFieldDefinition();
            $this->afterGetFieldDefinition();

            $this->validateCycles([]);
        }

        return $this->arguments;
    }

    final public function getTypeKind() : string
    {
        return \Graphpinator\Type\Introspection\TypeKind::INPUT_OBJECT;
    }

    final public function accept(\Graphpinator\Typesystem\NamedTypeVisitor $visitor) : mixed
    {
        return $visitor->visitInput($this);
    }

    final public function getDataClass() : string
    {
        return static::DATA_CLASS;
    }

    final public function addDirective(
        \Graphpinator\Directive\Contract\InputObjectLocation $directive,
        array $arguments = [],
    ) : static
    {
        $usage = new \Graphpinator\Directive\DirectiveUsage($directive, $arguments);

        if (!$directive->validateInputUsage($this, $usage->getArgumentValues())) {
            throw new \Graphpinator\Exception\Type\DirectiveIncorrectType();
        }

        $this->directiveUsages[] = $usage;

        return $this;
    }

    abstract protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet;

    /**
     * This function serves to prevent infinite cycles.
     *
     * It doesn't have to be used at all, unless input have arguments self referencing fields and wish to put default value for them.
     */
    protected function afterGetFieldDefinition() : void
    {
    }

    private function validateCycles(array $stack) : void
    {
        if ($this->cycleValidated) {
            return;
        }

        if (\array_key_exists($this->getName(), $stack)) {
            throw new \Graphpinator\Exception\Type\InputCycle();
        }

        $stack[$this->getName()] = true;

        foreach ($this->arguments as $argumentContract) {
            $type = $argumentContract->getType();

            if (!$type instanceof NotNullType) {
                continue;
            }

            $type = $type->getInnerType();

            if (!$type instanceof self) {
                continue;
            }

            if ($type->arguments === null) {
                $type->arguments = $type->getFieldDefinition();
            }

            $type->validateCycles($stack);
        }

        unset($stack[$this->getName()]);
        $this->cycleValidated = true;
    }
}
