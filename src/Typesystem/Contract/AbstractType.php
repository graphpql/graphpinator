<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Contract;

//@phpcs:ignore SlevomatCodingStandard.Classes.SuperfluousAbstractClassNaming.SuperfluousPrefix
abstract class AbstractType extends \Graphpinator\Typesystem\Contract\NamedType implements
    \Graphpinator\Typesystem\Contract\Outputable,
    \Graphpinator\Typesystem\Contract\TypeConditionable
{
    abstract public function isImplementedBy(\Graphpinator\Typesystem\Contract\Type $type) : bool;

    abstract public function createResolvedValue(mixed $rawValue) : \Graphpinator\Value\TypeIntermediateValue;
}
