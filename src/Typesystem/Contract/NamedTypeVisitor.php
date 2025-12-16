<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Contract;

use Graphpinator\Typesystem\EnumType;
use Graphpinator\Typesystem\InputType;
use Graphpinator\Typesystem\InterfaceType;
use Graphpinator\Typesystem\ScalarType;
use Graphpinator\Typesystem\Type;
use Graphpinator\Typesystem\UnionType;

/**
 * @phpcs:ignore
 * @template T of mixed
 */
interface NamedTypeVisitor
{
    /**
     * @return T
     */
    public function visitType(Type $type) : mixed;

    /**
     * @return T
     */
    public function visitInterface(InterfaceType $interface) : mixed;

    /**
     * @return T
     */
    public function visitUnion(UnionType $union) : mixed;

    /**
     * @return T
     */
    public function visitInput(InputType $input) : mixed;

    /**
     * @return T
     */
    public function visitScalar(ScalarType $scalar) : mixed;

    /**
     * @return T
     */
    public function visitEnum(EnumType $enum) : mixed;
}
