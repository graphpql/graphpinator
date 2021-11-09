<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Contract;

use \Graphpinator\Typesystem\DirectiveUsage\DirectiveUsage;

interface ComponentVisitor extends EntityVisitor
{
    public function visitField(\Graphpinator\Typesystem\Field\Field $field) : mixed;

    public function visitArgument(\Graphpinator\Typesystem\Argument\Argument $argument) : mixed;

    public function visitDirectiveUsage(DirectiveUsage $directiveUsage) : mixed;

    public function visitEnumItem(\Graphpinator\Typesystem\EnumItem\EnumItem $enumItem) : mixed;
}
