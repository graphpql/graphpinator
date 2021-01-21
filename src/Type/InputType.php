<?php

declare(strict_types = 1);

namespace Graphpinator\Type;

abstract class InputType extends \Graphpinator\Type\Contract\ConcreteDefinition implements \Graphpinator\Type\Contract\Inputable
{
    use \Graphpinator\Printable\TRepeatablePrint;
    use \Graphpinator\Utils\TObjectConstraint;

    protected const DATA_CLASS = \stdClass::class;

    protected ?\Graphpinator\Argument\ArgumentSet $arguments = null;
    private bool $cycleValidated = false;

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

            $this->validateCycles([]);
        }

        return $this->arguments;
    }

    final public function getTypeKind() : string
    {
        return \Graphpinator\Type\Introspection\TypeKind::INPUT_OBJECT;
    }

    final public function printSchema() : string
    {
        return $this->printDescription()
            . 'input ' . $this->getName() . $this->printConstraints() . ' {' . \PHP_EOL
            . $this->printItems($this->getArguments(), 1)
            . '}';
    }

    final public function getDataClass() : string
    {
        return static::DATA_CLASS;
    }

    abstract protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet;

    private function validateCycles(array $stack) : void
    {
        if ($this->cycleValidated) {
            return;
        }

        if (\array_key_exists($this->getName(), $stack)) {
            throw new \Graphpinator\Exception\Type\InputCycle();
        }

        foreach ($this->arguments as $argumentContract) {
            $type = $argumentContract->getType();

            if (!$type instanceof NotNullType) {
                continue;
            }

            $type = $type->getInnerType();

            if ($type instanceof ListType || $type instanceof \Graphpinator\Type\Contract\LeafDefinition) {
                continue;
            }

            \assert($type instanceof self);

            if ($type->arguments === null) {
                $type->arguments = $type->getFieldDefinition();
            }

            $stack[$this->getName()] = true;
            $type->validateCycles($stack);
            unset($stack[$this->getName()]);
        }

        $this->cycleValidated = true;
    }
}
