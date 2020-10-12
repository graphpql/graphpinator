<?php

declare(strict_types = 1);

namespace Graphpinator\Argument;

final class Argument implements \Graphpinator\Printable\Printable
{
    use \Nette\SmartObject;
    use \Graphpinator\Utils\TOptionalDescription;
    use \Graphpinator\Utils\THasConstraints;

    private string $name;
    private \Graphpinator\Type\Contract\Inputable $type;
    private ?\Graphpinator\Value\InputedValue $defaultValue;

    public function __construct(string $name, \Graphpinator\Type\Contract\Inputable $type, $defaultValue = null)
    {
        $this->name = $name;
        $this->type = $type;
        $this->constraints = new \Graphpinator\Constraint\ConstraintSet([]);

        if (\func_num_args() === 3) {
            $defaultValue = $type->createInputedValue($defaultValue);
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

    public function getDefaultValue() : ?\Graphpinator\Value\InputedValue
    {
        return $this->defaultValue;
    }

    public function addConstraint(\Graphpinator\Constraint\ArgumentConstraint $constraint) : self
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

        if ($this->defaultValue instanceof \Graphpinator\Value\InputedValue) {
            $schema .= ' = ' . $this->defaultValue->prettyPrint($indentLevel);
        }

        $schema .= $this->printConstraints();

        return $schema;
    }
}
