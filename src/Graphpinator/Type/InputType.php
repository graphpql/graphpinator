<?php

declare(strict_types = 1);

namespace Infinityloop\Graphpinator\Type;

abstract class InputType extends \Infinityloop\Graphpinator\Type\Contract\ConcreteDefinition implements \Infinityloop\Graphpinator\Type\Contract\Inputable
{
    protected \Infinityloop\Graphpinator\Argument\ArgumentSet $arguments;

    public function __construct(\Infinityloop\Graphpinator\Argument\ArgumentSet $arguments)
    {
        $this->arguments = $arguments;
    }

    public function createValue($rawValue) : \Infinityloop\Graphpinator\Value\ValidatedValue
    {
        return \Infinityloop\Graphpinator\Value\InputValue::create($rawValue, $this);
    }

    public function applyDefaults($value) : array
    {
        if (!\is_array($value)) {
            throw new \Exception('Composite input type without fields specified.');
        }

        return self::merge($value, $this->arguments->getDefaults());
    }

    public function getArguments() : \Infinityloop\Graphpinator\Argument\ArgumentSet
    {
        return $this->arguments;
    }

    private static function merge(array $core, iterable $supplement) : array
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
