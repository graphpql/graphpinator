<?php

declare(strict_types = 1);

namespace Graphpinator\Argument;

final class Argument implements \Graphpinator\Printable\Printable
{
    use \Nette\SmartObject;
    use \Graphpinator\Utils\TOptionalDescription;
    use \Graphpinator\Utils\TFieldConstraint;

    private ?\Graphpinator\Value\InputedValue $defaultValue = null;

    public function __construct(
        private string $name,
        private \Graphpinator\Type\Contract\Inputable $type,
        \stdClass|array|string|int|float|bool|null $defaultValue = null
    )
    {
        $this->constraints = new \Graphpinator\Constraint\ArgumentFieldConstraintSet([]);

        if (\func_num_args() === 3) {
            $this->defaultValue = $type->createInputedValue($defaultValue);
        }
    }

    public static function create(
        string $name,
        \Graphpinator\Type\Contract\Inputable $type,
        \stdClass|array|string|int|float|bool|null $defaultValue = null
    ) : self
    {
        return new self($name, $type, $defaultValue);
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

    public function printSchema(int $indentLevel) : string
    {
        $schema = $this->printDescription($indentLevel) . $this->getName() . ': ' . $this->type->printName();

        if ($this->defaultValue instanceof \Graphpinator\Value\InputedValue) {
            $schema .= ' = ' . $this->defaultValue->prettyPrint($indentLevel);
        }

        $schema .= $this->printConstraints();

        return $schema;
    }
}
