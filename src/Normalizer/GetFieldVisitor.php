<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer;

final class GetFieldVisitor implements \Graphpinator\Typesystem\Contract\NamedTypeVisitor
{
    public function __construct(
        private string $name,
    )
    {
    }

    public function visitType(\Graphpinator\Typesystem\Type $type) : \Graphpinator\Typesystem\Field\Field
    {
        return $type->getMetaFields()[$this->name]
            ?? $type->getFields()[$this->name]
            ?? throw new \Graphpinator\Normalizer\Exception\UnknownField($this->name, $type->getName());
    }

    public function visitInterface(\Graphpinator\Typesystem\InterfaceType $interface) : \Graphpinator\Typesystem\Field\Field
    {
        return $interface->getMetaFields()[$this->name]
            ?? $interface->getFields()[$this->name]
            ?? throw new \Graphpinator\Normalizer\Exception\UnknownField($this->name, $interface->getName());
    }

    public function visitUnion(\Graphpinator\Typesystem\UnionType $union) : \Graphpinator\Typesystem\Field\Field
    {
        return $union->getMetaFields()[$this->name]
            ?? throw new \Graphpinator\Normalizer\Exception\SelectionOnUnion();
    }

    public function visitInput(\Graphpinator\Typesystem\InputType $input) : mixed
    {
        // nothing here
    }

    public function visitScalar(\Graphpinator\Typesystem\ScalarType $scalar) : \Graphpinator\Typesystem\Field\Field
    {
        throw new \Graphpinator\Normalizer\Exception\SelectionOnLeaf();
    }

    public function visitEnum(\Graphpinator\Typesystem\EnumType $enum) : \Graphpinator\Typesystem\Field\Field
    {
        throw new \Graphpinator\Normalizer\Exception\SelectionOnLeaf();
    }
}
