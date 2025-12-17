<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Contract;

use Graphpinator\Typesystem\EnumType;
use Graphpinator\Typesystem\InputType;
use Graphpinator\Typesystem\ScalarType;
use Graphpinator\Typesystem\Type;

/**
 * @template T
 * @template-extends AbstractTypeVisitor<T>
 */
interface NamedTypeVisitor extends AbstractTypeVisitor
{
    /**
     * @return T
     */
    public function visitType(Type $type) : mixed;

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
