<?php

declare(strict_types = 1);

namespace Graphpinator\Resolver;

use \Graphpinator\Normalizer\Selection\SelectionSet;
use \Graphpinator\Value\ResolvedValue;

final class ResolveVisitor implements \Graphpinator\Typesystem\Contract\TypeVisitor
{
    public function __construct(
        private ?SelectionSet $selectionSet,
        private ResolvedValue $parentResult,
        private ?\stdClass $result = null,
    )
    {
        $this->result ??= new \stdClass();
    }

    public function visitType(\Graphpinator\Typesystem\Type $type) : \Graphpinator\Value\TypeValue
    {
        \assert($this->selectionSet instanceof SelectionSet);

        foreach ($this->selectionSet as $selectionEntity) {
            $selectionEntity->accept(new ResolveSelectionVisitor($this->parentResult, $this->result));
        }

        return new \Graphpinator\Value\TypeValue($type, $this->result);
    }

    public function visitInterface(\Graphpinator\Typesystem\InterfaceType $interface) : mixed
    {
        // nothing here
    }

    public function visitUnion(\Graphpinator\Typesystem\UnionType $union) : mixed
    {
        // nothing here
    }

    public function visitInput(\Graphpinator\Typesystem\InputType $input) : mixed
    {
        // nothing here
    }

    public function visitScalar(\Graphpinator\Typesystem\ScalarType $scalar) : ResolvedValue
    {
        return $this->parentResult;
    }

    public function visitEnum(\Graphpinator\Typesystem\EnumType $enum) : ResolvedValue
    {
        return $this->parentResult;
    }

    public function visitNotNull(\Graphpinator\Typesystem\NotNullType $notNull) : ResolvedValue
    {
        return $notNull->getInnerType()->accept($this);
    }

    public function visitList(\Graphpinator\Typesystem\ListType $list) : \Graphpinator\Value\ListResolvedValue
    {
        \assert($this->parentResult instanceof \Graphpinator\Value\ListIntermediateValue);

        $result = [];

        foreach ($this->parentResult->getRawValue() as $rawValue) {
            $value = $list->getInnerType()->accept(new CreateResolvedValueVisitor($rawValue));
            $result[] = $value instanceof \Graphpinator\Value\NullValue
                ? $value
                : $value->getType()->accept(new self($this->selectionSet, $value));
        }

        return new \Graphpinator\Value\ListResolvedValue($list, $result);
    }
}
