<?php

declare(strict_types = 1);

namespace PGQL\Type;

abstract class Input extends ConcreteDefinition implements Inputable
{
    protected \PGQL\Argument\ArgumentSet $arguments;

    public function __construct(\PGQL\Argument\ArgumentSet $arguments)
    {
        $this->arguments = $arguments;
    }

    public function getFields() : \PGQL\Argument\ArgumentSet
    {
        return $this->arguments;
    }

    public function applyDefaults($value) : array
    {
        if (!\is_array($value)) {
            throw new \Exception('Composite input type without fields specified.');
        }

        return self::merge($value, $this->arguments->getDefaults());
    }

    protected function validateNonNullValue($rawValue) : void
    {
        if (\is_array($rawValue)) {
            foreach ($this->arguments as $argument) {
                $usedValue = $rawValue[$argument->getName()] ?? $argument->getDefaultValue();

                // default values are already validated
                if ($usedValue instanceof \PGQL\Value\ValidatedValue) {
                    continue;
                }

                $argument->getType()->validateValue($usedValue);
            }

            foreach ($rawValue as $name => $argumentValue) {
                if (isset($this->arguments[$name])) {
                    continue;
                }

                throw new \Exception('Unknown field for input value');
            }

            return;
        }

        throw new \Exception();
    }

    private static function merge(array $core, array $supplement) : array
    {
        foreach ($supplement as $key => $value) {
            if (\array_key_exists($key, $core)) {
                $core[$key] = self::merge($core[$key], $supplement[$key]);

                continue;
            }

            $core[$key] = $value;
        }

        return $core;
    }
}
