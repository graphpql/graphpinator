<?php

declare(strict_types = 1);

namespace Graphpinator\Resolver;

use \Graphpinator\Value\ResolvedValue;
use \Nette\InvalidStateException;

final class ResolveVisitor implements \Graphpinator\Typesystem\Contract\TypeVisitor
{
    public function __construct(
        private ?\Graphpinator\Normalizer\Selection\SelectionSet $selectionSet,
        private ResolvedValue $parentResult,
        private \stdClass $result = new \stdClass(),
    )
    {
    }

    public function visitType(\Graphpinator\Typesystem\Type $type) : \Graphpinator\Value\TypeValue
    {
        \assert($this->parentResult instanceof \Graphpinator\Value\TypeIntermediateValue);
        \assert($this->selectionSet instanceof \Graphpinator\Normalizer\Selection\SelectionSet);

        foreach ($this->selectionSet as $selectionEntity) {
            $selectionEntity->accept(new ResolveSelectionVisitor($this->parentResult, $this->result));
        }

        return new \Graphpinator\Value\TypeValue($type, $this->result, $this->parentResult);
    }

    public function visitInterface(\Graphpinator\Typesystem\InterfaceType $interface) : mixed
    {
        throw new InvalidStateException();
    }

    public function visitUnion(\Graphpinator\Typesystem\UnionType $union) : mixed
    {
        throw new InvalidStateException();
    }

    public function visitInput(\Graphpinator\Typesystem\InputType $input) : mixed
    {
        throw new InvalidStateException();
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
