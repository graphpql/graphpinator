<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem;

interface EntityVisitor
{
    public function visitSchema(\Graphpinator\Type\Schema $schema) : mixed;

    public function visitType(\Graphpinator\Type\Type $type) : mixed;

    public function visitInterface(\Graphpinator\Type\InterfaceType $interface) : mixed;

    public function visitUnion(\Graphpinator\Type\UnionType $union) : mixed;

    public function visitInput(\Graphpinator\Type\InputType $input) : mixed;

    public function visitScalar(\Graphpinator\Type\Scalar\ScalarType $scalar) : mixed;

    public function visitEnum(\Graphpinator\Type\EnumType $enum) : mixed;

    public function visitDirective(\Graphpinator\Directive\Directive $directive) : mixed;
}
