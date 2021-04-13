<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer;

final class GetFieldVisitor implements \Graphpinator\Typesystem\NamedTypeVisitor
{
    public function __construct(
        private string $name
    ) {}

    public function visitType(\Graphpinator\Type\Type $type) : \Graphpinator\Field\Field
    {
        return $type->getMetaFields()[$this->name]
            ?? $type->getFields()[$this->name]
            ?? throw new \Graphpinator\Normalizer\Exception\UnknownField($this->name, $type->getName());
    }

    public function visitInterface(\Graphpinator\Type\InterfaceType $interface) : \Graphpinator\Field\Field
    {
        return $interface->getMetaFields()[$this->name]
            ?? $interface->getFields()[$this->name]
            ?? throw new \Graphpinator\Normalizer\Exception\UnknownField($this->name, $interface->getName());
    }

    public function visitUnion(\Graphpinator\Type\UnionType $union) : \Graphpinator\Field\Field
    {
        return $union->getMetaFields()[$this->name]
            ?? throw new \Graphpinator\Normalizer\Exception\SelectionOnUnion();
    }

    public function visitInput(\Graphpinator\Type\InputType $input) : mixed
    {
        // nothing here
    }

    public function visitScalar(\Graphpinator\Type\ScalarType $scalar) : \Graphpinator\Field\Field
    {
        throw new \Graphpinator\Normalizer\Exception\SelectionOnLeaf();
    }

    public function visitEnum(\Graphpinator\Type\EnumType $enum) : \Graphpinator\Field\Field
    {
        throw new \Graphpinator\Normalizer\Exception\SelectionOnLeaf();
    }
}
