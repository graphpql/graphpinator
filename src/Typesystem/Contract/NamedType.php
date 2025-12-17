<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Contract;

use Graphpinator\Typesystem\ListType;
use Graphpinator\Typesystem\NotNullType;
use Graphpinator\Typesystem\Utils\THasDescription;

abstract class NamedType implements Type, Entity
{
    use THasDescription;

    protected const NAME = '';

    /**
     * @template T of mixed
     * @param NamedTypeVisitor<T> $visitor
     * @return T
     */
    #[\Override]
    abstract public function accept(NamedTypeVisitor $visitor) : mixed;

    final public function getName() : string
    {
        return static::NAME;
    }

    final public function notNull() : NotNullType
    {
        return new NotNullType($this);
    }

    final public function notNullList() : NotNullType
    {
        return new NotNullType(new ListType(new NotNullType($this)));
    }

    final public function list() : ListType
    {
        return new ListType($this);
    }
}
