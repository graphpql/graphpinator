<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Contract;

abstract class NamedType implements \Graphpinator\Typesystem\Contract\Type, \Graphpinator\Typesystem\Contract\Entity
{
    use \Nette\SmartObject;
    use \Graphpinator\Typesystem\Utils\THasDescription;

    protected const NAME = '';

    abstract public function isInstanceOf(\Graphpinator\Typesystem\Contract\Type $type) : bool;

    abstract public function accept(\Graphpinator\Typesystem\Contract\NamedTypeVisitor $visitor) : mixed;

    final public function getName() : string
    {
        return static::NAME;
    }

    final public function printName() : string
    {
        return $this->getName();
    }

    final public function getNamedType() : \Graphpinator\Typesystem\Contract\NamedType
    {
        return $this;
    }

    final public function getShapingType() : \Graphpinator\Typesystem\Contract\Type
    {
        return $this;
    }

    final public function isInputable() : bool
    {
        return $this instanceof Inputable;
    }

    final public function notNull() : \Graphpinator\Typesystem\NotNullType
    {
        return new \Graphpinator\Typesystem\NotNullType($this);
    }

    final public function notNullList() : \Graphpinator\Typesystem\NotNullType
    {
        return new \Graphpinator\Typesystem\NotNullType(new \Graphpinator\Typesystem\ListType(new \Graphpinator\Typesystem\NotNullType($this)));
    }

    final public function list() : \Graphpinator\Typesystem\ListType
    {
        return new \Graphpinator\Typesystem\ListType($this);
    }
}
