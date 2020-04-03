<?php

declare(strict_types = 1);

namespace Graphpinator\Parser;

final class Variable
{
    use \Nette\SmartObject;

    private string $name;
    private \Graphpinator\Parser\TypeRef\TypeRef $type;
    private ?\Graphpinator\Parser\Value\Value $default;

    public function __construct(
        string $name,
        \Graphpinator\Parser\TypeRef\TypeRef $type,
        ?\Graphpinator\Parser\Value\Value $default = null
    ) {
        $this->name = $name;
        $this->type = $type;
        $this->default = $default;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getType() : \Graphpinator\Parser\TypeRef\TypeRef
    {
        return $this->type;
    }

    public function getDefault() : ?\Graphpinator\Parser\Value\Value
    {
        return $this->default;
    }

    public function createValue(
        \Infinityloop\Utils\Json $variableValues,
        \Graphpinator\DI\TypeResolver $typeResolver
    ) : \Graphpinator\Value\ValidatedValue
    {
        $value = null;

        if (isset($variableValues[$this->name])) {
            $value = $variableValues[$this->name];
        } elseif ($this->default instanceof \Graphpinator\Parser\Value\Value) {
            $value = $this->default;
        }

        return \Graphpinator\Value\ValidatedValue::create(
            $value,
            $this->type->resolve($typeResolver),
        );
    }
}
