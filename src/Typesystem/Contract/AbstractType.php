<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Contract;

//@phpcs:ignore SlevomatCodingStandard.Classes.SuperfluousAbstractClassNaming.SuperfluousPrefix
abstract class AbstractType extends NamedType implements Outputable, TypeConditionable
{
    abstract public function isImplementedBy(Type $type) : bool;

    abstract public function createResolvedValue(mixed $rawValue) : \Graphpinator\Value\TypeIntermediateValue;
}
