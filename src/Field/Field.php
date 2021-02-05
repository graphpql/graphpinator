<?php

declare(strict_types = 1);

namespace Graphpinator\Field;

class Field implements \Graphpinator\Printable\Printable
{
    use \Nette\SmartObject;
    use \Graphpinator\Utils\TOptionalDescription;
    use \Graphpinator\Directive\THasDirectives;
    use \Graphpinator\Directive\TDeprecatable;
    use \Graphpinator\Printable\TRepeatablePrint;

    protected string $name;
    protected \Graphpinator\Type\Contract\Outputable $type;
    protected \Graphpinator\Argument\ArgumentSet $arguments;

    public function __construct(string $name, \Graphpinator\Type\Contract\Outputable $type)
    {
        $this->name = $name;
        $this->type = $type;
        $this->arguments = new \Graphpinator\Argument\ArgumentSet([]);
        $this->directives = new \Graphpinator\Directive\DirectiveUsageSet([]);
        $this->directiveLocation = \Graphpinator\Directive\TypeSystemDirectiveLocation::FIELD_DEFINITION;
    }

    public static function create(string $name, \Graphpinator\Type\Contract\Outputable $type) : self
    {
        return new self($name, $type);
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

    public function setArguments(\Graphpinator\Argument\ArgumentSet $arguments) : static
    {
        $this->arguments = $arguments;

        return $this;
    }

    public function printSchema(int $indentLevel) : string
    {
        return $this->printDescription($indentLevel)
            . $this->getName() . $this->printArguments() . ': ' . $this->getType()->printName() . $this->printDirectives();
    }

    private function printArguments() : string
    {
        if (\count($this->arguments) === 0) {
            return '';
        }

        return '(' . \PHP_EOL . $this->printItems($this->getArguments(), 2) . '  )';
    }
}
