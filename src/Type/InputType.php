<?php

declare(strict_types = 1);

namespace Graphpinator\Type;

abstract class InputType extends \Graphpinator\Type\Contract\ConcreteDefinition implements \Graphpinator\Type\Contract\Inputable
{
    use \Graphpinator\Printable\TRepeatablePrint;
    use \Graphpinator\Utils\THasConstraints;

    protected ?\Graphpinator\Argument\ArgumentSet $arguments = null;

    final public function createInputedValue($rawValue) : \Graphpinator\Value\InputedValue
    {
        if ($rawValue instanceof \stdClass) {
            return new \Graphpinator\Value\InputValue($this, $rawValue);
        }

        return new \Graphpinator\Value\NullInputedValue($this);
    }

    final public function getArguments() : \Graphpinator\Argument\ArgumentSet
    {
        if (!$this->arguments instanceof \Graphpinator\Argument\ArgumentSet) {
            $this->arguments = $this->getFieldDefinition();
        }

        return $this->arguments;
    }

    final public function getTypeKind() : string
    {
        return \Graphpinator\Type\Introspection\TypeKind::INPUT_OBJECT;
    }

    public function addConstraint(\Graphpinator\Constraint\InputConstraint $constraint) : self
    {
        if (!$constraint->validateType($this)) {
            throw new \Graphpinator\Exception\Constraint\InvalidConstraintType();
        }

        $this->getConstraints()[] = $constraint;

        return $this;
    }

    final public function printSchema() : string
    {
        return $this->printDescription()
            . 'input ' . $this->getName() . $this->printConstraints() . ' {' . \PHP_EOL
            . $this->printItems($this->getArguments())
            . '}';
    }

    abstract protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet;
}
