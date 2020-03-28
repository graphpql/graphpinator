<?php

declare(strict_types = 1);

namespace PGQL\Type\Scalar;

abstract class EnumType extends ScalarType
{
    protected array $options;

    public function __construct(array $options)
    {
        $this->options = $options;
    }

    public static function fromConstants() : array
    {
        $values = [];

        foreach ((new \ReflectionClass(static::class))->getReflectionConstants() as $constant) {
            $value = $constant->getValue();

            if (!\is_string($value) || !$constant->isPublic()) {
                continue;
            }

            $values[$value] = \strtoupper($value);
        }

        return $values;
    }

    public function getAll() : array
    {
        return \array_keys($this->options);
    }

    protected function validateNonNullValue($rawValue) : void
    {
        if (\is_string($rawValue) && \array_key_exists($rawValue, $this->options)) {
            return;
        }

        throw new \Exception('Unkwown enum value');
    }
}
