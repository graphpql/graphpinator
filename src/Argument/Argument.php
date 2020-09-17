<?php

declare(strict_types = 1);

namespace Graphpinator\Argument;

final class Argument implements \Graphpinator\Printable\Printable
{
    use \Nette\SmartObject;
    use \Graphpinator\Utils\TOptionalDescription;

    private string $name;
    private \Graphpinator\Type\Contract\Inputable $type;
    private \Graphpinator\Argument\Constraint\ConstraintSet $constraints;
    private ?\Graphpinator\Resolver\Value\ValidatedValue $defaultValue;

    public function __construct(string $name, \Graphpinator\Type\Contract\Inputable $type, $defaultValue = null)
    {
        $this->name = $name;
        $this->type = $type;
        $this->constraints = new \Graphpinator\Argument\Constraint\ConstraintSet([]);

        if (\func_num_args() === 3) {
            $defaultValue = $type->createValue($defaultValue);
        }

        $this->defaultValue = $defaultValue;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getType() : \Graphpinator\Type\Contract\Inputable
    {
        return $this->type;
    }

    public function getDefaultValue() : ?\Graphpinator\Resolver\Value\ValidatedValue
    {
        return $this->defaultValue;
    }

    public function getConstraints() : ?\Graphpinator\Argument\Constraint\ConstraintSet
    {
        return $this->constraints;
    }

    public function validateConstraints(\Graphpinator\Resolver\Value\ValidatedValue $value) : void
    {
        foreach ($this->constraints as $constraint) {
            $constraint->validate($value);
        }
    }

    public function addConstraint(\Graphpinator\Argument\Constraint\Constraint $constraint) : self
    {
        if (!$constraint->validateType($this->type)) {
            throw new \Graphpinator\Exception\Constraint\InvalidConstraintType();
        }

        $this->constraints[] = $constraint;

        return $this;
    }

    public function printSchema(int $indentLevel = 1) : string
    {
        $schema = $this->printDescription($indentLevel) . $this->getName() . ': ' . $this->type->printName();

        if ($this->defaultValue instanceof \Graphpinator\Resolver\Value\ValidatedValue) {
            $schema .= ' = ' . $this->defaultValue->printValue();
        }

        foreach ($this->constraints as $constraint) {
            $schema .= ' ' . $constraint->printConstraint();
        }

        return $schema;
    }
}
