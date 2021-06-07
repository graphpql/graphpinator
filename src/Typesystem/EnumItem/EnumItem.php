<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\EnumItem;

final class EnumItem implements \Graphpinator\Typesystem\Contract\Component
{
    use \Nette\SmartObject;
    use \Graphpinator\Typesystem\Utils\TOptionalDescription;
    use \Graphpinator\Typesystem\Utils\THasDirectives;
    use \Graphpinator\Typesystem\Utils\TDeprecatable;

    public function __construct(
        private string $name,
        ?string $description = null,
    )
    {
        $this->description = $description;
        $this->directiveUsages = new \Graphpinator\Typesystem\DirectiveUsage\DirectiveUsageSet();
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function accept(\Graphpinator\Typesystem\Contract\ComponentVisitor $visitor) : mixed
    {
        return $visitor->visitEnumItem($this);
    }

    public function addDirective(
        \Graphpinator\Typesystem\Location\EnumItemLocation $directive,
        array $arguments = [],
    ) : self
    {
        $this->directiveUsages[] = new \Graphpinator\Typesystem\DirectiveUsage\DirectiveUsage($directive, $arguments);

        return $this;
    }
}
