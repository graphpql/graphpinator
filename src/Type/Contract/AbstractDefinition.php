<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Contract;

//@phpcs:ignore SlevomatCodingStandard.Classes.SuperfluousAbstractClassNaming.SuperfluousPrefix
abstract class AbstractDefinition extends \Graphpinator\Type\Contract\NamedDefinition implements
    \Graphpinator\Type\Contract\Outputable,
    \Graphpinator\Type\Contract\TypeConditionable
{
    abstract public function isImplementedBy(\Graphpinator\Type\Contract\Definition $type) : bool;

    abstract public function createResolvedValue(mixed $rawValue) : \Graphpinator\Value\TypeIntermediateValue;
}
