<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer;

use \Graphpinator\Typesystem\Field\Field;

final class GetFieldVisitor implements \Graphpinator\Typesystem\Contract\NamedTypeVisitor
{
    public function __construct(
        private string $name,
    )
    {
    }

    public function visitType(\Graphpinator\Typesystem\Type $type) : Field
    {
        return $type->getMetaFields()[$this->name]
            ?? $type->getFields()[$this->name]
            ?? throw new \Graphpinator\Normalizer\Exception\UnknownField($this->name, $type->getName());
    }

    public function visitInterface(\Graphpinator\Typesystem\InterfaceType $interface) : Field
    {
        return $interface->getMetaFields()[$this->name]
            ?? $interface->getFields()[$this->name]
            ?? throw new \Graphpinator\Normalizer\Exception\UnknownField($this->name, $interface->getName());
    }

    public function visitUnion(\Graphpinator\Typesystem\UnionType $union) : Field
    {
        return $union->getMetaFields()[$this->name]
            ?? throw new \Graphpinator\Normalizer\Exception\SelectionOnUnion();
    }

    public function visitInput(\Graphpinator\Typesystem\InputType $input) : mixed
    {
        throw new \LogicException();
    }

    public function visitScalar(\Graphpinator\Typesystem\ScalarType $scalar) : Field
    {
        throw new \Graphpinator\Normalizer\Exception\SelectionOnLeaf();
    }

    public function visitEnum(\Graphpinator\Typesystem\EnumType $enum) : Field
    {
        throw new \Graphpinator\Normalizer\Exception\SelectionOnLeaf();
    }
}
