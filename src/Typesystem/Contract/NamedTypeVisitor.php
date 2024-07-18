<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Contract;

use Graphpinator\Typesystem\EnumType;
use Graphpinator\Typesystem\InputType;
use Graphpinator\Typesystem\InterfaceType;
use Graphpinator\Typesystem\ScalarType;
use Graphpinator\Typesystem\Type;
use Graphpinator\Typesystem\UnionType;

interface NamedTypeVisitor
{
    public function visitType(Type $type) : mixed;

    public function visitInterface(InterfaceType $interface) : mixed;

    public function visitUnion(UnionType $union) : mixed;

    public function visitInput(InputType $input) : mixed;

    public function visitScalar(ScalarType $scalar) : mixed;

    public function visitEnum(EnumType $enum) : mixed;
}
