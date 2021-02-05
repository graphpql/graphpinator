<?php

declare(strict_types = 1);

namespace Graphpinator\Argument;

final class Argument implements \Graphpinator\Printable\Printable
{
    use \Nette\SmartObject;
    use \Graphpinator\Utils\TOptionalDescription;
    use \Graphpinator\Directive\THasDirectives;

    private ?\Graphpinator\Value\InputedValue $defaultValue = null;

    public function __construct(
        private string $name,
        private \Graphpinator\Type\Contract\Inputable $type,
    )
    {
        $this->directives = new \Graphpinator\Directive\DirectiveUsageSet();
        $this->directiveLocation = \Graphpinator\Directive\TypeSystemDirectiveLocation::ARGUMENT_DEFINITION;
    }

    public static function create(string $name, \Graphpinator\Type\Contract\Inputable $type) : self
    {
        return new self($name, $type);
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

    public function setDefaultValue(\stdClass|array|string|int|float|bool|null $defaultValue) : self
    {
        $this->defaultValue = $this->type->createInputedValue($defaultValue);

        return $this;
    }

    public function printSchema(int $indentLevel) : string
    {
        $schema = $this->printDescription($indentLevel) . $this->getName() . ': ' . $this->type->printName();

        if ($this->defaultValue instanceof \Graphpinator\Value\InputedValue) {
            $schema .= ' = ' . $this->defaultValue->prettyPrint($indentLevel);
        }

        $schema .= $this->printDirectives();

        return $schema;
    }
}
