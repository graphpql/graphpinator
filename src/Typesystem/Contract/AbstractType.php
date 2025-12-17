<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Contract;

use Graphpinator\Value\TypeIntermediateValue;

//@phpcs:ignore SlevomatCodingStandard.Classes.SuperfluousAbstractClassNaming.SuperfluousPrefix
abstract class AbstractType extends NamedType implements TypeConditionable
{
    /**
     * @template T of mixed
     * @param AbstractTypeVisitor<T> $visitor
     * @return T
     */
    #[\Override]
    abstract public function accept(AbstractTypeVisitor $visitor) : mixed;

    abstract public function createResolvedValue(mixed $rawValue) : TypeIntermediateValue;
}
