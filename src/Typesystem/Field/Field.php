<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Field;

use Graphpinator\Graphpinator;
use Graphpinator\Typesystem\Argument\ArgumentSet;
use Graphpinator\Typesystem\Contract\Component;
use Graphpinator\Typesystem\Contract\ComponentVisitor;
use Graphpinator\Typesystem\Contract\Type;
use Graphpinator\Typesystem\DirectiveUsage\DirectiveUsage;
use Graphpinator\Typesystem\DirectiveUsage\DirectiveUsageSet;
use Graphpinator\Typesystem\Exception\DirectiveIncorrectType;
use Graphpinator\Typesystem\Exception\FieldInvalidTypeUsage;
use Graphpinator\Typesystem\Location\FieldDefinitionLocation;
use Graphpinator\Typesystem\Utils\TDeprecatable;
use Graphpinator\Typesystem\Utils\THasDirectives;
use Graphpinator\Typesystem\Utils\TOptionalDescription;
use Graphpinator\Typesystem\Visitor\IsOutputableVisitor;
use Graphpinator\Typesystem\Visitor\PrintNameVisitor;

class Field implements Component
{
    use TOptionalDescription;
    use THasDirectives;
    use TDeprecatable;

    protected ArgumentSet $arguments;

    public function __construct(
        protected string $name,
        protected Type $type,
    )
    {
        if (Graphpinator::$validateSchema && !$this->type->accept(new IsOutputableVisitor())) {
            throw new FieldInvalidTypeUsage($this->name, $this->type->accept(new PrintNameVisitor()));
        }

        $this->arguments = new ArgumentSet([]);
        $this->directiveUsages = new DirectiveUsageSet();
    }

    public static function create(string $name, Type $type) : self
    {
        return new self($name, $type);
    }

    final public function getName() : string
    {
        return $this->name;
    }

    final public function getType() : Type
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

    #[\Override]
    final public function accept(ComponentVisitor $visitor) : mixed
    {
        return $visitor->visitField($this);
    }

    /**
     * @param FieldDefinitionLocation $directive
     * @phpcs:ignore
     * @param array<string, mixed> $arguments
     */
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
