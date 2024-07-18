<?php

declare(strict_types = 1);

namespace Graphpinator\Introspection;

use Graphpinator\Typesystem\Attribute\Description;
use Graphpinator\Typesystem\EnumItem\EnumItem;
use Graphpinator\Typesystem\EnumItem\EnumItemSet;
use Graphpinator\Typesystem\EnumType;
use Graphpinator\Typesystem\Location\ExecutableDirectiveLocation;
use Graphpinator\Typesystem\Location\TypeSystemDirectiveLocation;

#[Description('Built-in introspection type')]
final class DirectiveLocation extends EnumType
{
    protected const NAME = '__DirectiveLocation';

    public function __construct()
    {
        $values = [];
        $ref = new \ReflectionEnum(ExecutableDirectiveLocation::class);

        foreach ($ref->getCases() as $case) {
            $values[] = new EnumItem($case->getBackingValue());
        }

        $ref = new \ReflectionEnum(TypeSystemDirectiveLocation::class);

        foreach ($ref->getCases() as $case) {
            $values[] = new EnumItem($case->getBackingValue());
        }

        parent::__construct(new EnumItemSet($values));
    }
}
