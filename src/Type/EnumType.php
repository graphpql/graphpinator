<?php

declare(strict_types = 1);

namespace Graphpinator\Type;

abstract class EnumType extends \Graphpinator\Type\Contract\LeafDefinition
{
    public function __construct(
        protected \Graphpinator\EnumItem\EnumItemSet $options,
    ) {}

    final public static function fromConstants() : \Graphpinator\EnumItem\EnumItemSet
    {
        $values = [];

        foreach ((new \ReflectionClass(static::class))->getReflectionConstants() as $constant) {
            $value = $constant->getValue();

            if (!$constant->isPublic()) {
                continue;
            }

            $values[] = new \Graphpinator\EnumItem\EnumItem($value, $constant->getDocComment()
                ? \trim($constant->getDocComment(), '/* ')
                : null);
        }

        return new \Graphpinator\EnumItem\EnumItemSet($values);
    }

    final public function getItems() : \Graphpinator\EnumItem\EnumItemSet
    {
        return $this->options;
    }

    final public function accept(\Graphpinator\Typesystem\NamedTypeVisitor $visitor) : mixed
    {
        return $visitor->visitEnum($this);
    }

    final public function validateNonNullValue(mixed $rawValue) : bool
    {
        return \is_string($rawValue) && $this->options->offsetExists($rawValue);
    }

    public function createInputedValue(string|int|float|bool|null|array|\stdClass|\Psr\Http\Message\UploadedFileInterface $rawValue) : \Graphpinator\Value\InputedValue
    {
        if ($rawValue === null) {
            return new \Graphpinator\Value\NullInputedValue($this);
        }

        return new \Graphpinator\Value\EnumValue($this, $rawValue, true);
    }
}
