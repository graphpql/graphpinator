<?php

declare(strict_types = 1);

namespace Graphpinator\Introspection;

#[\Graphpinator\Typesystem\Attribute\Description('Built-in introspection type')]
final class DirectiveLocation extends \Graphpinator\Typesystem\EnumType
{
    protected const NAME = '__DirectiveLocation';

    public function __construct()
    {
        $values = [];
        $ref = new \ReflectionEnum(\Graphpinator\Typesystem\Location\ExecutableDirectiveLocation::class);

        foreach ($ref->getCases() as $case) {
            $values[] = new \Graphpinator\Typesystem\EnumItem\EnumItem($case->getBackingValue());
        }

        $ref = new \ReflectionEnum(\Graphpinator\Typesystem\Location\TypeSystemDirectiveLocation::class);

        foreach ($ref->getCases() as $case) {
            $values[] = new \Graphpinator\Typesystem\EnumItem\EnumItem($case->getBackingValue());
        }

        parent::__construct(new \Graphpinator\Typesystem\EnumItem\EnumItemSet($values));
    }
}
