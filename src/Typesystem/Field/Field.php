<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Field;

use Graphpinator\Graphpinator;
use Graphpinator\Typesystem\Argument\ArgumentSet;
use Graphpinator\Typesystem\Contract\Component;
use Graphpinator\Typesystem\Contract\ComponentVisitor;
use Graphpinator\Typesystem\Contract\Outputable;
use Graphpinator\Typesystem\DirectiveUsage\DirectiveUsage;
use Graphpinator\Typesystem\DirectiveUsage\DirectiveUsageSet;
use Graphpinator\Typesystem\Exception\DirectiveIncorrectType;
use Graphpinator\Typesystem\Location\FieldDefinitionLocation;
use Graphpinator\Typesystem\Utils\TDeprecatable;
use Graphpinator\Typesystem\Utils\THasDirectives;
use Graphpinator\Typesystem\Utils\TOptionalDescription;

class Field implements Component
{
    use TOptionalDescription;
    use THasDirectives;
    use TDeprecatable;

    protected ArgumentSet $arguments;

    public function __construct(
        protected string $name,
        protected Outputable $type,
    )
    {
        $this->arguments = new ArgumentSet([]);
        $this->directiveUsages = new DirectiveUsageSet();
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

    final public function accept(ComponentVisitor $visitor) : mixed
    {
        return $visitor->visitField($this);
    }

    final public function addDirective(
        FieldDefinitionLocation $directive,
        array $arguments = [],
    ) : self
    {
        $usage = new DirectiveUsage($directive, $arguments);

        if (Graphpinator::$validateSchema && !$directive->validateFieldUsage($this, $usage->getArgumentValues())) {
            throw new DirectiveIncorrectType();
        }

        $this->directiveUsages[] = $usage;

        return $this;
    }
}
