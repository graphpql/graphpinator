<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Contract;

interface NamedTypeVisitor
{
    public function visitType(\Graphpinator\Typesystem\Type $type) : mixed;

    public function visitInterface(\Graphpinator\Typesystem\InterfaceType $interface) : mixed;

    public function visitUnion(\Graphpinator\Typesystem\UnionType $union) : mixed;

    public function visitInput(\Graphpinator\Typesystem\InputType $input) : mixed;

    public function visitScalar(\Graphpinator\Typesystem\ScalarType $scalar) : mixed;

    public function visitEnum(\Graphpinator\Typesystem\EnumType $enum) : mixed;
}
