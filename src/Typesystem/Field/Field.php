<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Field;

use \Graphpinator\Typesystem\Argument\ArgumentSet;
use \Graphpinator\Typesystem\Contract\Outputable;

class Field implements \Graphpinator\Typesystem\Contract\Component
{
    use \Nette\SmartObject;
    use \Graphpinator\Typesystem\Utils\TOptionalDescription;
    use \Graphpinator\Typesystem\Utils\THasDirectives;
    use \Graphpinator\Typesystem\Utils\TDeprecatable;

    protected ArgumentSet $arguments;

    public function __construct(
        protected string $name,
        protected Outputable $type,
    )
    {
        $this->arguments = new ArgumentSet([]);
        $this->directiveUsages = new \Graphpinator\Typesystem\DirectiveUsage\DirectiveUsageSet();
    }

    public static function create(string $name, Outputable $type) : self
    {
        return new self($name, $type);
    }

    final public function getName() : string
    {
        return $this->name;
    }

    final public function getType() : Outputable
    {
        return $this->type;
    }

    final public function getArguments() : ArgumentSet
    {
        return $this->arguments;
    }

    final public function setArguments(ArgumentSet $arguments) : static
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
