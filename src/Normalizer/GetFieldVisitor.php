<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer;

use \Graphpinator\Normalizer\Exception\SelectionOnLeaf;
use \Graphpinator\Normalizer\Exception\SelectionOnUnion;
use \Graphpinator\Normalizer\Exception\UnknownField;
use \Graphpinator\Typesystem\Contract\NamedTypeVisitor;
use \Graphpinator\Typesystem\Field\Field;

final class GetFieldVisitor implements NamedTypeVisitor
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
            ?? throw new UnknownField($this->name, $type->getName());
    }

    public function visitInterface(\Graphpinator\Typesystem\InterfaceType $interface) : Field
    {
        return $interface->getMetaFields()[$this->name]
            ?? $interface->getFields()[$this->name]
            ?? throw new UnknownField($this->name, $interface->getName());
    }

    public function visitUnion(\Graphpinator\Typesystem\UnionType $union) : Field
    {
        return $union->getMetaFields()[$this->name]
            ?? throw new SelectionOnUnion();
    }

    public function visitInput(\Graphpinator\Typesystem\InputType $input) : mixed
    {
        // nothing here
    }

    public function visitScalar(\Graphpinator\Typesystem\ScalarType $scalar) : Field
    {
        throw new SelectionOnLeaf();
    }

    public function visitEnum(\Graphpinator\Typesystem\EnumType $enum) : Field
    {
        throw new SelectionOnLeaf();
    }
}
