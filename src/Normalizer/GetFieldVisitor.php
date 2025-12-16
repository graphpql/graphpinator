<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer;

use Graphpinator\Normalizer\Exception\SelectionOnLeaf;
use Graphpinator\Normalizer\Exception\SelectionOnUnion;
use Graphpinator\Normalizer\Exception\UnknownField;
use Graphpinator\Typesystem\Contract\NamedTypeVisitor;
use Graphpinator\Typesystem\EnumType;
use Graphpinator\Typesystem\Field\Field;
use Graphpinator\Typesystem\InputType;
use Graphpinator\Typesystem\InterfaceType;
use Graphpinator\Typesystem\ScalarType;
use Graphpinator\Typesystem\Type;
use Graphpinator\Typesystem\UnionType;

final class GetFieldVisitor implements NamedTypeVisitor
{
    public function __construct(
        private string $name,
    )
    {
    }

    #[\Override]
    public function visitType(Type $type) : Field
    {
        return $type->getMetaFields()[$this->name]
            ?? $type->getFields()[$this->name]
            ?? throw new UnknownField($this->name, $type->getName());
    }

    #[\Override]
    public function visitInterface(InterfaceType $interface) : Field
    {
        return $interface->getMetaFields()[$this->name]
            ?? $interface->getFields()[$this->name]
            ?? throw new UnknownField($this->name, $interface->getName());
    }

    #[\Override]
    public function visitUnion(UnionType $union) : Field
    {
        return $union->getMetaFields()[$this->name]
            ?? throw new SelectionOnUnion();
    }

    #[\Override]
    public function visitInput(InputType $input) : mixed
    {
        throw new \LogicException();
    }

    #[\Override]
    public function visitScalar(ScalarType $scalar) : Field
    {
        throw new SelectionOnLeaf();
    }

    #[\Override]
    public function visitEnum(EnumType $enum) : Field
    {
        throw new SelectionOnLeaf();
    }
}
