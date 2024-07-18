<?php

declare(strict_types = 1);

namespace Graphpinator\Resolver;

use Graphpinator\Normalizer\Selection\SelectionSet;
use Graphpinator\Typesystem\Contract\TypeVisitor;
use Graphpinator\Typesystem\EnumType;
use Graphpinator\Typesystem\InputType;
use Graphpinator\Typesystem\InterfaceType;
use Graphpinator\Typesystem\ListType;
use Graphpinator\Typesystem\NotNullType;
use Graphpinator\Typesystem\ScalarType;
use Graphpinator\Typesystem\Type;
use Graphpinator\Typesystem\UnionType;
use Graphpinator\Value\ListIntermediateValue;
use Graphpinator\Value\ListResolvedValue;
use Graphpinator\Value\NullValue;
use Graphpinator\Value\ResolvedValue;
use Graphpinator\Value\TypeIntermediateValue;
use Graphpinator\Value\TypeValue;

final class ResolveVisitor implements TypeVisitor
{
    public function __construct(
        private ?SelectionSet $selectionSet,
        private ResolvedValue $parentResult,
        private \stdClass $result = new \stdClass(),
    )
    {
    }

    public function visitType(Type $type) : TypeValue
    {
        \assert($this->parentResult instanceof TypeIntermediateValue);
        \assert($this->selectionSet instanceof SelectionSet);

        foreach ($this->selectionSet as $selectionEntity) {
            $selectionEntity->accept(new ResolveSelectionVisitor($this->parentResult, $this->result));
        }

        return new TypeValue($type, $this->result, $this->parentResult);
    }

    public function visitInterface(InterfaceType $interface) : mixed
    {
        throw new \LogicException();
    }

    public function visitUnion(UnionType $union) : mixed
    {
        throw new \LogicException();
    }

    public function visitInput(InputType $input) : mixed
    {
        throw new \LogicException();
    }

    public function visitScalar(ScalarType $scalar) : ResolvedValue
    {
        return $this->parentResult;
    }

    public function visitEnum(EnumType $enum) : ResolvedValue
    {
        return $this->parentResult;
    }

    public function visitNotNull(NotNullType $notNull) : ResolvedValue
    {
        return $notNull->getInnerType()->accept($this);
    }

    public function visitList(ListType $list) : ListResolvedValue
    {
        \assert($this->parentResult instanceof ListIntermediateValue);

        $result = [];

        foreach ($this->parentResult->getRawValue() as $rawValue) {
            $value = $list->getInnerType()->accept(new CreateResolvedValueVisitor($rawValue));
            $result[] = $value instanceof NullValue
                ? $value
                : $value->getType()->accept(new self($this->selectionSet, $value));
        }

        return new ListResolvedValue($list, $result);
    }
}
