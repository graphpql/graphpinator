<?php

declare(strict_types = 1);

namespace Graphpinator\EnumItem;

final class EnumItem implements \Graphpinator\Typesystem\Component
{
    use \Nette\SmartObject;
    use \Graphpinator\Utils\TOptionalDescription;
    use \Graphpinator\Utils\THasDirectives;
    use \Graphpinator\Utils\TDeprecatable;
    use \Graphpinator\Utils\TSpecifiable;

    private string $name;

    public function __construct(string $name, ?string $description = null)
    {
        $this->name = $name;
        $this->description = $description;
        $this->directiveUsages = new \Graphpinator\DirectiveUsage\DirectiveUsageSet();
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function accept(\Graphpinator\Typesystem\ComponentVisitor $visitor) : mixed
    {
        return $visitor->visitEnumItem($this);
    }

    public function addDirective(
        \Graphpinator\Directive\Contract\EnumItemLocation $directive,
        array $arguments = [],
    ) : self
    {
        $this->directiveUsages[] = new \Graphpinator\DirectiveUsage\DirectiveUsage($directive, $arguments);

        return $this;
    }
}
