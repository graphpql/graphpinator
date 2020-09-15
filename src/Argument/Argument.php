<?php

declare(strict_types = 1);

namespace Graphpinator\Argument;

final class Argument implements \Graphpinator\Printable\Printable
{
    use \Nette\SmartObject;
    use \Graphpinator\Utils\TOptionalDescription;

    private string $name;
    private \Graphpinator\Type\Contract\Inputable $type;
    private ?\Graphpinator\Resolver\Value\ValidatedValue $defaultValue;

    public function __construct(string $name, \Graphpinator\Type\Contract\Inputable $type, $defaultValue = null)
    {
        $this->name = $name;
        $this->type = $type;

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

    public function printSchema(int $indentLevel = 1) : string
    {
        $schema = $this->printDescription($indentLevel) . $this->getName() . ': ' . $this->type->printName();

        if ($this->defaultValue instanceof \Graphpinator\Resolver\Value\ValidatedValue) {
            $schema .= ' = ' . $this->defaultValue->printValue(true);
        }

        return $schema;
    }
}
