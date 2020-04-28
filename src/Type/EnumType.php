<?php

declare(strict_types = 1);

namespace Graphpinator\Type;

abstract class EnumType extends \Graphpinator\Type\Contract\LeafDefinition
{
    protected Enum\EnumItemSet $options;

    public function __construct(Enum\EnumItemSet $options)
    {
        $this->options = $options;
    }

    public static function fromConstants() : Enum\EnumItemSet
    {
        $values = [];

        foreach ((new \ReflectionClass(static::class))->getReflectionConstants() as $constant) {
            $value = $constant->getValue();

            if (!\is_string($value) || !$constant->isPublic()) {
                continue;
            }

            $values[] = new Enum\EnumItem(\strtoupper($value));
        }

        return new Enum\EnumItemSet($values);
    }

    public function getItems() : Enum\EnumItemSet
    {
        return $this->options;
    }

    public function getTypeKind() : string
    {
        return \Graphpinator\Type\Introspection\TypeKind::ENUM;
    }

    public function printSchema() : string
    {
        $schema = 'enum ' . $this->getName() . ' {' . \PHP_EOL;

        foreach ($this->getItems() as $enumItem) {
            $schema .= '  ' . $enumItem->printSchema() . \PHP_EOL;
        }

        return $schema . '}';
    }

    protected function validateNonNullValue($rawValue) : bool
    {
        return \is_string($rawValue) && $this->options->offsetExists($rawValue);
    }
}
