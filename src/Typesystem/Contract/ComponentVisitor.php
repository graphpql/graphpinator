<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Contract;

use Graphpinator\Typesystem\Argument\Argument;
use Graphpinator\Typesystem\DirectiveUsage\DirectiveUsage;
use Graphpinator\Typesystem\EnumItem\EnumItem;
use Graphpinator\Typesystem\Field\Field;

/**
 * @template T
 * @template-extends EntityVisitor<T>
 */
interface ComponentVisitor extends EntityVisitor
{
    /**
     * @return T
     */
    public function visitField(Field $field) : mixed;

    /**
     * @return T
     */
    public function visitArgument(Argument $argument) : mixed;

    /**
     * @return T
     */
    public function visitDirectiveUsage(DirectiveUsage $directiveUsage) : mixed;

    /**
     * @return T
     */
    public function visitEnumItem(EnumItem $enumItem) : mixed;
}
