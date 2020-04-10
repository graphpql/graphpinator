<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Scalar;

abstract class EnumType extends ScalarType
{
    protected EnumItemSet $options;

    public function __construct(EnumItemSet $options)
    {
        $this->options = $options;
    }

    public static function fromConstants() : EnumItemSet
    {
        $values = [];

        foreach ((new \ReflectionClass(static::class))->getReflectionConstants() as $constant) {
            $value = $constant->getValue();

            if (!\is_string($value) || !$constant->isPublic()) {
                continue;
            }

            $values[] = new EnumItem(\strtoupper($value));
        }

        return new EnumItemSet($values);
    }

    public function getItems() : EnumItemSet
    {
        return $this->options;
    }

    protected function validateNonNullValue($rawValue) : void
    {
        if (\is_string($rawValue) && $this->options->offsetExists($rawValue)) {
            return;
        }

        throw new \Exception('Unkwown enum value');
    }

    public function getTypeKind() : string
    {
        return \Graphpinator\Type\Introspection\TypeKind::ENUM;
    }
}
