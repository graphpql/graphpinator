<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\Value;

final class ArgumentValue
{
    use \Nette\SmartObject;

    public function __construct(
        private \Graphpinator\Parser\Value\Value $value,
        private string $name,
    ) {}

    public function getValue() : \Graphpinator\Parser\Value\Value
    {
        return $this->value;
    }

    public function getRawValue() : \stdClass|array|string|int|float|bool|null
    {
        return $this->value->getRawValue();
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function normalize(\Graphpinator\Container\Container $typeContainer) : \Graphpinator\Normalizer\Value\ArgumentValue
    {
        return new \Graphpinator\Normalizer\Value\ArgumentValue(
            $this->value->normalize($typeContainer),
            $this->name,
        );
    }
}
