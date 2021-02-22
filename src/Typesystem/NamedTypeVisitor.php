<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem;

interface NamedTypeVisitor
{
    public function visitType(\Graphpinator\Type\Type $type) : mixed;

    public function visitInterface(\Graphpinator\Type\InterfaceType $interface) : mixed;

    public function visitUnion(\Graphpinator\Type\UnionType $union) : mixed;

    public function visitInput(\Graphpinator\Type\InputType $input) : mixed;

    public function visitScalar(\Graphpinator\Type\Scalar\ScalarType $scalar) : mixed;

    public function visitEnum(\Graphpinator\Type\EnumType $enum) : mixed;
}
