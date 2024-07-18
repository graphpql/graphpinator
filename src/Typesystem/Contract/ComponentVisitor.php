<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Contract;

use Graphpinator\Typesystem\Argument\Argument;
use Graphpinator\Typesystem\DirectiveUsage\DirectiveUsage;
use Graphpinator\Typesystem\EnumItem\EnumItem;
use Graphpinator\Typesystem\Field\Field;

interface ComponentVisitor extends EntityVisitor
{
    public function visitField(Field $field) : mixed;

    public function visitArgument(Argument $argument) : mixed;

    public function visitDirectiveUsage(DirectiveUsage $directiveUsage) : mixed;

    public function visitEnumItem(EnumItem $enumItem) : mixed;
}
