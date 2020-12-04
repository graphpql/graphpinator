<?php

declare(strict_types = 1);

namespace Graphpinator\Type;

abstract class InputType extends \Graphpinator\Type\Contract\ConcreteDefinition implements \Graphpinator\Type\Contract\Inputable
{
    use \Graphpinator\Printable\TRepeatablePrint;
    use \Graphpinator\Utils\TObjectConstraint;

    protected ?\Graphpinator\Argument\ArgumentSet $arguments = null;

    final public function createInputedValue(mixed $rawValue) : \Graphpinator\Value\InputedValue
    {
        if ($rawValue instanceof \stdClass) {
            return \Graphpinator\Value\InputValue::fromRawValue($this, $rawValue);
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

    final public function printSchema() : string
    {
        return $this->printDescription()
            . 'input ' . $this->getName() . $this->printConstraints() . ' {' . \PHP_EOL
            . $this->printItems($this->getArguments(), 1)
            . '}';
    }

    abstract protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet;
}
