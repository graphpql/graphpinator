<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\Variable;

final class Variable
{
    use \Nette\SmartObject;

    public function __construct(
        private string $name,
        private \Graphpinator\Parser\TypeRef\TypeRef $type,
        private ?\Graphpinator\Parser\Value\Value $default = null,
    ) {}

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

    public function normalize(
        \Graphpinator\Container\Container $typeContainer,
    ) : \Graphpinator\Normalizer\Variable\Variable
    {
        return new \Graphpinator\Normalizer\Variable\Variable(
            $this->name,
            $this->type->normalize($typeContainer),
            $this->default,
        );
    }
}
