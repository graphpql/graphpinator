<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem;

use Graphpinator\Typesystem\Contract\AbstractType;
use Graphpinator\Typesystem\Contract\AbstractTypeVisitor;
use Graphpinator\Typesystem\Contract\InterfaceImplementor;
use Graphpinator\Typesystem\DirectiveUsage\DirectiveUsage;
use Graphpinator\Typesystem\DirectiveUsage\DirectiveUsageSet;
use Graphpinator\Typesystem\Field\FieldSet;
use Graphpinator\Typesystem\Location\ObjectLocation;
use Graphpinator\Typesystem\Utils\THasDirectives;
use Graphpinator\Typesystem\Utils\TInterfaceImplementor;
use Graphpinator\Typesystem\Utils\TMetaFields;

abstract class InterfaceType extends AbstractType implements InterfaceImplementor
{
    use TInterfaceImplementor;
    use TMetaFields;
    use THasDirectives;

    public function __construct(
        InterfaceSet $implements = new InterfaceSet([]),
    )
    {
        $this->implements = $implements;
        $this->directiveUsages = new DirectiveUsageSet();
    }

    #[\Override]
    final public function getFields() : FieldSet
    {
        if (!$this->fields instanceof FieldSet) {
            $this->fields = new FieldSet([]);

            foreach ($this->implements as $interfaceType) {
                $this->fields->merge($interfaceType->getFields(), true);
            }

            $this->fields->merge($this->getFieldDefinition(), true);
        }

        return $this->fields;
    }

    #[\Override]
    final public function accept(AbstractTypeVisitor $visitor) : mixed
    {
        return $visitor->visitInterface($this);
    }

    /**
     * @param ObjectLocation $directive
     * @phpcs:ignore
     * @param array<string, mixed> $arguments
     */
    final public function addDirective(ObjectLocation $directive, array $arguments = []) : static
    {
        $this->directiveUsages[] = new DirectiveUsage($directive, $arguments);

        return $this;
    }

    abstract protected function getFieldDefinition() : FieldSet;
}
