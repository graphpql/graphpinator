<?php

declare(strict_types = 1);

namespace PGQL\Type;

use PGQL\Type\Contract\ConcreteDefinition;
use PGQL\Type\Contract\Inputable;

abstract class InputType extends ConcreteDefinition implements Inputable
{
    protected \PGQL\Argument\ArgumentSet $arguments;

    public function __construct(\PGQL\Argument\ArgumentSet $arguments)
    {
        $this->arguments = $arguments;
    }

    public function createValue($rawValue) : \PGQL\Value\ValidatedValue
    {
        return \PGQL\Value\InputValue::create($rawValue, $this);
    }

    public function validateNonNullValue($rawValue): void
    {
        $this->createValue($rawValue);
    }

    public function applyDefaults($value) : array
    {
        if (!\is_array($value)) {
            throw new \Exception('Composite input type without fields specified.');
        }

        return self::merge($value, $this->arguments->getDefaults());
    }

    public function getArguments() : \PGQL\Argument\ArgumentSet
    {
        return $this->arguments;
    }

    private static function merge(array $core, array $supplement) : array
    {
        foreach ($supplement as $key => $value) {
            if (\array_key_exists($key, $core)) {
                if (\is_array($core[$key])) {
                    $core[$key] = self::merge($core[$key], $supplement[$key]);
                }

                continue;
            }

            $core[$key] = $value;
        }

        return $core;
    }
}
