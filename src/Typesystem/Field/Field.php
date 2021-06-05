<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Field;

class Field implements \Graphpinator\Typesystem\Contract\Component
{
    use \Nette\SmartObject;
    use \Graphpinator\Typesystem\Utils\TOptionalDescription;
    use \Graphpinator\Typesystem\Utils\THasDirectives;
    use \Graphpinator\Typesystem\Utils\TDeprecatable;

    protected \Graphpinator\Typesystem\Argument\ArgumentSet $arguments;

    public function __construct(protected string $name, protected \Graphpinator\Typesystem\Contract\Outputable $type)
    {
        $this->arguments = new \Graphpinator\Typesystem\Argument\ArgumentSet([]);
        $this->directiveUsages = new \Graphpinator\Typesystem\DirectiveUsage\DirectiveUsageSet();
    }

    public static function create(string $name, \Graphpinator\Typesystem\Contract\Outputable $type) : self
    {
        return new self($name, $type);
    }

    final public function getName() : string
    {
        return $this->name;
    }

    final public function getType() : \Graphpinator\Typesystem\Contract\Outputable
    {
        return $this->type;
    }

    final public function getArguments() : \Graphpinator\Typesystem\Argument\ArgumentSet
    {
        return $this->arguments;
    }

    final public function setArguments(\Graphpinator\Typesystem\Argument\ArgumentSet $arguments) : static
    {
        $this->arguments = $arguments;

        return $this;
    }

    final public function accept(\Graphpinator\Typesystem\Contract\ComponentVisitor $visitor) : mixed
    {
        return $visitor->visitField($this);
    }

    final public function addDirective(
        \Graphpinator\Typesystem\Location\FieldDefinitionLocation $directive,
        array $arguments = [],
    ) : self
    {
        $usage = new \Graphpinator\Typesystem\DirectiveUsage\DirectiveUsage($directive, $arguments);

        if (\Graphpinator\Graphpinator::$validateSchema && !$directive->validateFieldUsage($this, $usage->getArgumentValues())) {
            throw new \Graphpinator\Typesystem\Exception\DirectiveIncorrectType();
        }

        $this->directiveUsages[] = $usage;

        return $this;
    }
}
