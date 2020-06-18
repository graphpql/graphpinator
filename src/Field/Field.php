<?php

declare(strict_types = 1);

namespace Graphpinator\Field;

class Field
{
    use \Nette\SmartObject;
    use \Graphpinator\Utils\TOptionalDescription;
    use \Graphpinator\Utils\TDeprecatable;

    protected string $name;
    protected \Graphpinator\Type\Contract\Outputable $type;
    protected \Graphpinator\Argument\ArgumentSet $arguments;

    public function __construct(string $name, \Graphpinator\Type\Contract\Outputable $type, ?\Graphpinator\Argument\ArgumentSet $arguments = null)
    {
        $this->name = $name;
        $this->type = $type;
        $this->arguments = $arguments
            ?? new \Graphpinator\Argument\ArgumentSet([]);
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getType() : \Graphpinator\Type\Contract\Outputable
    {
        return $this->type;
    }

    public function getArguments() : \Graphpinator\Argument\ArgumentSet
    {
        return $this->arguments;
    }

    public function printSchema() : string
    {
        return $this->printDescription() . $this->getName() . $this->printArguments() . ': ' . $this->getType()->printName() . $this->printDeprecated();
    }

    public function printDescription() : string
    {
        if ($this->getDescription() !== null) {
            if (\mb_strpos($this->getDescription(), \PHP_EOL) !== false) {
                $description = \str_replace(\PHP_EOL, \PHP_EOL . '  ', $this->getDescription());
                return '"""' . \PHP_EOL . '  ' . $description . \PHP_EOL . '  ' . '"""' . \PHP_EOL . '  ';
            }

            return '"' . $this->getDescription() . '"' . \PHP_EOL . '  ';
        }

        return '';
    }

    private function printArguments() : string
    {
        if (\count($this->arguments) === 0) {
            return '';
        }

        $arguments = [];

        foreach ($this->arguments as $argument) {
            $arguments[] = $argument->printSchema();
        }

        return '(' . \implode(', ', $arguments) . ')';
    }
}
