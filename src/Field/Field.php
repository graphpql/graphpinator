<?php

declare(strict_types = 1);

namespace Graphpinator\Field;

class Field implements \Graphpinator\Typesystem\Component
{
    use \Nette\SmartObject;
    use \Graphpinator\Utils\TOptionalDescription;
    use \Graphpinator\Directive\THasDirectives;
    use \Graphpinator\Directive\TDeprecatable;

    protected string $name;
    protected \Graphpinator\Type\Contract\Outputable $type;
    protected \Graphpinator\Argument\ArgumentSet $arguments;

    public function __construct(string $name, \Graphpinator\Type\Contract\Outputable $type)
    {
        $this->name = $name;
        $this->type = $type;
        $this->arguments = new \Graphpinator\Argument\ArgumentSet([]);
        $this->directiveUsages = new \Graphpinator\Directive\DirectiveUsageSet([]);
    }

    public static function create(string $name, \Graphpinator\Type\Contract\Outputable $type) : self
    {
        return new self($name, $type);
    }

    final public function getName() : string
    {
        return $this->name;
    }

    final public function getType() : \Graphpinator\Type\Contract\Outputable
    {
        return $this->type;
    }

    final public function getArguments() : \Graphpinator\Argument\ArgumentSet
    {
        return $this->arguments;
    }

    final public function setArguments(\Graphpinator\Argument\ArgumentSet $arguments) : static
    {
        $this->arguments = $arguments;

        return $this;
    }

    final public function accept(\Graphpinator\Typesystem\ComponentVisitor $visitor) : mixed
    {
        return $visitor->visitField($this);
    }

    final public function addDirective(
        \Graphpinator\Directive\Contract\FieldDefinitionLocation $directive,
        array $arguments = [],
    ) : self
    {
        $usage = new \Graphpinator\Directive\DirectiveUsage($directive, $arguments);

        if (!$directive->validateFieldUsage($this, $usage->getArgumentValues())) {
            throw new \Graphpinator\Exception\Type\DirectiveIncorrectType();
        }

        $this->directiveUsages[] = $usage;

        return $this;
    }
}
