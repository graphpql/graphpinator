<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\EnumItem;

use Graphpinator\Typesystem\Contract\Component;
use Graphpinator\Typesystem\Contract\ComponentVisitor;
use Graphpinator\Typesystem\DirectiveUsage\DirectiveUsage;
use Graphpinator\Typesystem\DirectiveUsage\DirectiveUsageSet;
use Graphpinator\Typesystem\Location\EnumItemLocation;
use Graphpinator\Typesystem\Utils\TDeprecatable;
use Graphpinator\Typesystem\Utils\THasDirectives;
use Graphpinator\Typesystem\Utils\TOptionalDescription;

final class EnumItem implements Component
{
    use TOptionalDescription;
    use THasDirectives;
    use TDeprecatable;

    public function __construct(
        private string $name,
        ?string $description = null,
    )
    {
        $this->description = $description;
        $this->directiveUsages = new DirectiveUsageSet();
    }

    public function getName() : string
    {
        return $this->name;
    }

    #[\Override]
    public function accept(ComponentVisitor $visitor) : mixed
    {
        return $visitor->visitEnumItem($this);
    }

    /**
     * @param EnumItemLocation $directive
     * @phpcs:ignore
     * @param array<string, mixed> $arguments
     */
    public function addDirective(
        EnumItemLocation $directive,
        array $arguments = [],
    ) : self
    {
        $this->directiveUsages[] = new DirectiveUsage($directive, $arguments);

        return $this;
    }
}
