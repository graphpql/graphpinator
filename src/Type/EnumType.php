<?php

declare(strict_types = 1);

namespace Graphpinator\Type;

abstract class EnumType extends \Graphpinator\Type\Contract\LeafDefinition
{
    use \Graphpinator\Printable\TRepeatablePrint;

    protected Enum\EnumItemSet $options;

    public function __construct(Enum\EnumItemSet $options)
    {
        $this->options = $options;
    }

    final public static function fromConstants() : Enum\EnumItemSet
    {
        $values = [];

        foreach ((new \ReflectionClass(static::class))->getReflectionConstants() as $constant) {
            $value = $constant->getValue();

            if (!$constant->isPublic()) {
                continue;
            }

            if (\is_string($value)) {
                $values[] = new \Graphpinator\Type\Enum\EnumItem(\strtoupper($value));
            } elseif (\is_array($value) && \count($value) === 2) {
                $values[] = new \Graphpinator\Type\Enum\EnumItem(\strtoupper($value[0]), $value[1]);
            }
        }

        return new \Graphpinator\Type\Enum\EnumItemSet($values);
    }

    final public function getItems() : Enum\EnumItemSet
    {
        return $this->options;
    }

    final public function getTypeKind() : string
    {
        return \Graphpinator\Type\Introspection\TypeKind::ENUM;
    }

    final public function printSchema() : string
    {
        return $this->printDescription()
            . 'enum ' . $this->getName() . ' {' . \PHP_EOL
            . $this->printItems($this->getItems(), 1)
            . '}';
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
