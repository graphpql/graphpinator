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

            if (!$constant->isPublic()) {
                continue;
            }

            if (\is_string($value)) {
                $values[] = new Enum\EnumItem(\strtoupper($value));
            } elseif (\is_array($value) && \count($value) === 2) {
                $values[] = new Enum\EnumItem(\strtoupper($value[0]), $value[1]);
            }
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
        $schema = $this->printDescription() . 'enum ' . $this->getName() . ' {' . \PHP_EOL;

        $previousHasDescription = false;
        $isFirst = true;

        foreach ($this->getItems() as $enumItem) {
            $currentHasDescription = $enumItem->getDescription() !== null;

            if (!$isFirst && ($previousHasDescription || $currentHasDescription)) {
                $schema .= \PHP_EOL;
            }

            $schema .= $enumItem->printSchema() . \PHP_EOL;

            $previousHasDescription = $currentHasDescription;
            $isFirst = false;
        }

        return $schema . '}';
    }

    protected function validateNonNullValue($rawValue) : bool
    {
        return \is_string($rawValue) && $this->options->offsetExists($rawValue);
    }
}
