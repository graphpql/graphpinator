<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Visitor;

use Graphpinator\Typesystem\Contract\AbstractTypeVisitor;
use Graphpinator\Typesystem\Contract\InterfaceImplementor;
use Graphpinator\Typesystem\Contract\Type as TypeContract;
use Graphpinator\Typesystem\InterfaceType;
use Graphpinator\Typesystem\UnionType;

/**
 * @implements AbstractTypeVisitor<bool>
 */
final readonly class IsImplementedByVisitor implements AbstractTypeVisitor
{
    private TypeContract $typeToCompare;

    public function __construct(
        TypeContract $typeToCompare,
    )
    {
        $this->typeToCompare = $typeToCompare->accept(new GetShapingTypeVisitor());
    }

    #[\Override]
    public function visitInterface(InterfaceType $interface) : bool
    {
        return $this->typeToCompare instanceof InterfaceImplementor && $this->typeToCompare->implements($interface);
    }

    #[\Override]
    public function visitUnion(UnionType $union) : bool
    {
        foreach ($union->getTypes() as $unionItem) {
            if ($unionItem::class === $this->typeToCompare::class) {
                return true;
            }
        }

        return false;
    }
}
