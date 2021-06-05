<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Contract;

interface ComponentVisitor extends \Graphpinator\Typesystem\Contract\EntityVisitor
{
    public function visitField(\Graphpinator\Field\Field $field) : mixed;

    public function visitArgument(\Graphpinator\Argument\Argument $argument) : mixed;

    public function visitDirectiveUsage(\Graphpinator\DirectiveUsage\DirectiveUsage $directiveUsage) : mixed;

    public function visitEnumItem(\Graphpinator\EnumItem\EnumItem $enumItem) : mixed;
}
