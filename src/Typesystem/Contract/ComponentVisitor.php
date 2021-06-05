<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Contract;

interface ComponentVisitor extends \Graphpinator\Typesystem\Contract\EntityVisitor
{
    public function visitField(\Graphpinator\Typesystem\Field\Field $field) : mixed;

    public function visitArgument(\Graphpinator\Typesystem\Argument\Argument $argument) : mixed;

    public function visitDirectiveUsage(\Graphpinator\Typesystem\DirectiveUsage\DirectiveUsage $directiveUsage) : mixed;

    public function visitEnumItem(\Graphpinator\Typesystem\EnumItem\EnumItem $enumItem) : mixed;
}
